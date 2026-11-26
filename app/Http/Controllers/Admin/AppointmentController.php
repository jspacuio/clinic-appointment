<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAppointmentRequest;
use App\Http\Requests\CancelAppointmentRequest;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentWorkflowService;
use App\Services\WeeklyScheduleBuilder;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(
        private readonly AppointmentWorkflowService $workflow,
        private readonly WeeklyScheduleBuilder $scheduleBuilder,
    ) {}

    public function index(Request $request): View
    {
        $statusFilter = $request->string('status')->toString();
        $search = trim($request->string('search')->toString());
        $fromDate = $request->string('from')->toString();
        $toDate = $request->string('to')->toString();

        $baseQuery = Appointment::with(['patient:id,full_name,patient_code,avatar_tone', 'doctor:id,full_name,specialization']);

        if ($statusFilter !== '' && $statusFilter !== 'all') {
            $baseQuery->where('status', $statusFilter);
        }

        if ($search !== '') {
            $baseQuery->where(function ($query) use ($search): void {
                $query->where('appointment_code', 'like', "%{$search}%")
                    ->orWhereHas('patient', fn ($q) => $q->where('full_name', 'like', "%{$search}%")->orWhere('patient_code', 'like', "%{$search}%"))
                    ->orWhereHas('doctor', fn ($q) => $q->where('full_name', 'like', "%{$search}%"));
            });
        }

        if ($fromDate !== '') {
            $baseQuery->whereDate('appointment_date', '>=', $fromDate);
        }

        if ($toDate !== '') {
            $baseQuery->whereDate('appointment_date', '<=', $toDate);
        }

        $appointments = $baseQuery
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Appointment::select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        $totalCount = array_sum($statusCounts);

        return view('admin.appointments.index', [
            'appointments' => $appointments,
            'statuses' => AppointmentStatus::cases(),
            'statusCounts' => $statusCounts,
            'totalCount' => $totalCount,
            'activeStatus' => $statusFilter !== '' ? $statusFilter : 'all',
            'search' => $search,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function create(Request $request): View
    {
        $patients = Patient::where('is_active', true)->orderBy('full_name')->get();
        $doctors = Doctor::where('is_active', true)->orderBy('full_name')->get();

        $selectedDoctorId = $request->integer('doctor_id') ?: ($doctors->first()?->id ?? 0);
        $selectedDoctor = $doctors->firstWhere('id', $selectedDoctorId) ?? $doctors->first();
        $selectedPatientId = $request->integer('patient_id') ?: ($patients->first()?->id ?? 0);

        $today = CarbonImmutable::today();
        $dates = [];
        for ($i = 0; $i < 5; $i++) {
            $date = $today->addDays($i);
            $dates[] = [
                'value' => $date->toDateString(),
                'day' => $date->format('D'),
                'num' => $date->format('d'),
                'full' => $date->format('l, F j, Y'),
            ];
        }

        $selectedDate = $request->string('date')->toString() ?: $dates[0]['value'];
        $slots = $selectedDoctor !== null
            ? $this->scheduleBuilder->availableBookingSlots($selectedDoctor, $selectedDate)
            : [];

        return view('admin.appointments.create', [
            'patients' => $patients,
            'doctors' => $doctors,
            'specializations' => $doctors->pluck('specialization')->unique()->values(),
            'selectedPatientId' => $selectedPatientId,
            'selectedDoctor' => $selectedDoctor,
            'dates' => $dates,
            'selectedDate' => $selectedDate,
            'slots' => $slots,
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $doctor = Doctor::findOrFail($request->integer('doctor_id'));

        $appointment = Appointment::create([
            'appointment_code' => Appointment::generateNextCode(),
            'patient_id' => $request->integer('patient_id'),
            'doctor_id' => $doctor->id,
            'booked_by_user_id' => $request->user()->id,
            'appointment_date' => $request->string('appointment_date')->toString(),
            'start_time' => $request->string('start_time')->toString(),
            'duration_minutes' => $doctor->consultation_duration,
            'visit_type' => $request->string('visit_type')->toString(),
            'reason' => $request->input('reason'),
            'status' => AppointmentStatus::Pending,
        ]);

        AuditLog::record(
            $request->user(),
            'Booked appointment',
            "Appointment #{$appointment->appointment_code}",
            "Scheduled with {$doctor->full_name} (awaiting doctor & patient confirmation)"
        );

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status', "Appointment #{$appointment->appointment_code} has been created and awaits doctor & patient confirmation.");
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load([
            'patient',
            'doctor',
            'bookedBy:id,name,role',
        ]);

        return view('admin.appointments.show', [
            'appointment' => $appointment,
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::enum(AppointmentStatus::class)],
        ]);

        $nextStatus = AppointmentStatus::from($validated['status']);

        if (in_array($nextStatus, [
            AppointmentStatus::InProgress,
            AppointmentStatus::Completed,
        ], true)) {
            abort(403, 'Admin does not start or finish appointments.');
        }

        $this->workflow->transitionStatus($appointment, $nextStatus, $request->user());

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status', "Appointment status updated to {$nextStatus->label()}.");
    }

    public function reschedule(Request $request, Appointment $appointment): RedirectResponse
    {
        $validated = $request->validate([
            'appointment_date' => ['required', 'date'],
            'start_time' => ['required', 'date_format:H:i:s'],
        ]);

        $hasConflict = Appointment::where('doctor_id', $appointment->doctor_id)
            ->where('id', '!=', $appointment->id)
            ->whereDate('appointment_date', $validated['appointment_date'])
            ->where('start_time', $validated['start_time'])
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value, AppointmentStatus::NoShow->value])
            ->exists();

        if ($hasConflict) {
            return back()->withErrors([
                'start_time' => 'Selected slot is already booked for this doctor.',
            ]);
        }

        $oldSummary = $appointment->appointment_date->format('M j, Y').' '.$appointment->startTimeFormatted();

        $appointment->update([
            'appointment_date' => $validated['appointment_date'],
            'start_time' => $validated['start_time'],
        ]);

        $newSummary = $appointment->appointment_date->format('M j, Y').' '.$appointment->startTimeFormatted();

        AuditLog::record(
            $request->user(),
            'Rescheduled appointment',
            "Appointment #{$appointment->appointment_code}",
            "{$oldSummary} → {$newSummary}"
        );

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status', 'Appointment rescheduled successfully.');
    }

    public function cancel(CancelAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $this->workflow->cancel(
            $appointment,
            $request->string('cancellation_reason')->toString(),
            $request->input('cancellation_notes'),
            $request->user()
        );

        return redirect()
            ->route('admin.appointments.show', $appointment)
            ->with('status', 'Appointment has been cancelled.');
    }
}
