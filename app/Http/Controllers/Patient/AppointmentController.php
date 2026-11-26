<?php

namespace App\Http\Controllers\Patient;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelAppointmentRequest;
use App\Http\Requests\Patient\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentWorkflowService;
use App\Services\WeeklyScheduleBuilder;
use Carbon\CarbonImmutable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentWorkflowService $workflow,
        private readonly WeeklyScheduleBuilder $scheduleBuilder,
    ) {}

    private function resolvePatient(Request $request): Patient
    {
        $user = $request->user();
        $patient = $user->patient;

        if ($patient === null) {
            $patient = Patient::create([
                'user_id' => $user->id,
                'patient_code' => Patient::generateNextCode(),
                'full_name' => $user->name,
                'sex' => 'Prefer not to say',
                'phone' => $user->phone ?: 'Not provided',
                'email' => $user->email,
                'avatar_tone' => 'teal',
                'is_active' => true,
            ]);
        }

        return $patient;
    }

    private function ensurePatientOwnsAppointment(Patient $patient, Appointment $appointment): void
    {
        if ($appointment->patient_id !== $patient->id) {
            abort(404);
        }
    }

    public function index(Request $request): View
    {
        $patient = $this->resolvePatient($request);
        $statusFilter = $request->string('status')->toString();
        $search = trim($request->string('search')->toString());

        $query = Appointment::with('doctor:id,full_name,specialization,avatar_tone')
            ->where('patient_id', $patient->id);

        if ($statusFilter !== '' && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('appointment_code', 'like', "%{$search}%")
                    ->orWhere('visit_type', 'like', "%{$search}%")
                    ->orWhereHas('doctor', fn ($d) => $d->where('full_name', 'like', "%{$search}%")->orWhere('specialization', 'like', "%{$search}%"));
            });
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Appointment::where('patient_id', $patient->id)
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        return view('patient.appointments.index', [
            'patient' => $patient,
            'appointments' => $appointments,
            'statuses' => AppointmentStatus::cases(),
            'statusCounts' => $statusCounts,
            'totalCount' => array_sum($statusCounts),
            'activeStatus' => $statusFilter !== '' ? $statusFilter : 'all',
            'search' => $search,
        ]);
    }

    public function create(Request $request): View
    {
        $patient = $this->resolvePatient($request);

        $categories = Doctor::where('is_active', true)
            ->select('specialization', DB::raw('COUNT(*) as doctors_count'), DB::raw('MIN(consultation_duration) as min_duration'))
            ->groupBy('specialization')
            ->orderBy('specialization')
            ->get();

        $requestedCategory = $request->string('category')->toString();
        $selectedCategory = $categories->contains('specialization', $requestedCategory)
            ? $requestedCategory
            : ($categories->first()?->specialization ?? '');

        $today = CarbonImmutable::today();
        $dates = [];
        for ($i = 0; $i < 7; $i++) {
            $date = $today->addDays($i);
            $dates[] = [
                'value' => $date->toDateString(),
                'day' => $date->format('D'),
                'num' => $date->format('d'),
                'month' => $date->format('M'),
                'full' => $date->format('l, F j, Y'),
            ];
        }

        $requestedDate = $request->string('date')->toString();
        $selectedDate = ($requestedDate !== '' && $requestedDate >= $today->toDateString())
            ? $requestedDate
            : $dates[0]['value'];

        $slots = $selectedCategory !== ''
            ? $this->scheduleBuilder->availableBookingSlotsForSpecialization($selectedCategory, $selectedDate)
            : [];

        return view('patient.appointments.create', [
            'patient' => $patient,
            'categories' => $categories,
            'selectedCategory' => $selectedCategory,
            'dates' => $dates,
            'selectedDate' => $selectedDate,
            'slots' => $slots,
        ]);
    }

    public function slots(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'category' => ['required', 'string', 'max:120'],
            'date' => ['required', 'date', 'after_or_equal:today'],
        ]);

        $date = CarbonImmutable::parse($validated['date']);

        return response()->json([
            'date' => $date->toDateString(),
            'dateLabel' => $date->format('l, F j, Y'),
            'slots' => $this->scheduleBuilder->availableBookingSlotsForSpecialization(
                $validated['category'],
                $date->toDateString()
            ),
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $patient = $this->resolvePatient($request);
        $specialization = $request->string('specialization')->toString();
        $date = $request->string('appointment_date')->toString();
        $time = $request->string('start_time')->toString();

        [$appointment, $assignedDoctor] = DB::transaction(function () use ($request, $patient, $specialization, $date, $time): array {
            $eligibleDoctors = $this->scheduleBuilder->findAvailableDoctorsForSlot($specialization, $date, $time);

            if ($eligibleDoctors->isEmpty()) {
                throw ValidationException::withMessages([
                    'start_time' => 'No doctors for the selected concern are available at this time slot. Please choose another slot.',
                ]);
            }

            /** @var Doctor $doctor */
            $doctor = $eligibleDoctors->random();

            $created = Appointment::create([
                'appointment_code' => Appointment::generateNextCode(),
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'booked_by_user_id' => $request->user()->id,
                'appointment_date' => $date,
                'start_time' => $time,
                'duration_minutes' => $doctor->consultation_duration,
                'visit_type' => $request->string('visit_type')->toString(),
                'reason' => $request->input('reason'),
                'status' => AppointmentStatus::Pending,
            ]);

            return [$created, $doctor];
        });

        AuditLog::record(
            $request->user(),
            'Requested appointment',
            "Appointment #{$appointment->appointment_code}",
            "Concern: {$specialization} · Assigned {$assignedDoctor->full_name}"
        );

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('status', "Appointment #{$appointment->appointment_code} requested for {$specialization}. Assigned doctor: {$assignedDoctor->full_name}.");
    }

    public function show(Request $request, Appointment $appointment): View
    {
        $patient = $this->resolvePatient($request);
        $this->ensurePatientOwnsAppointment($patient, $appointment);

        $appointment->load([
            'patient',
            'doctor',
            'bookedBy:id,name,role',
        ]);

        return view('patient.appointments.show', [
            'patient' => $patient,
            'appointment' => $appointment,
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $patient = $this->resolvePatient($request);
        $this->ensurePatientOwnsAppointment($patient, $appointment);

        $request->validate([
            'status' => ['required', Rule::in([AppointmentStatus::Completed->value])],
        ]);

        $result = $this->workflow->confirmCompletion($appointment, $request->user());

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('status', $result['message']);
    }

    public function cancel(CancelAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $patient = $this->resolvePatient($request);
        $this->ensurePatientOwnsAppointment($patient, $appointment);

        $this->workflow->cancel(
            $appointment,
            $request->string('cancellation_reason')->toString(),
            $request->input('cancellation_notes'),
            $request->user()
        );

        return redirect()
            ->route('patient.appointments.show', $appointment)
            ->with('status', 'Your appointment has been cancelled.');
    }
}
