<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelAppointmentRequest;
use App\Http\Requests\Doctor\UpdateClinicalNotesRequest;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Services\AppointmentWorkflowService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function __construct(private readonly AppointmentWorkflowService $workflow) {}

    private function resolveDoctor(Request $request): Doctor
    {
        $doctor = $request->user()->doctor;
        abort_if($doctor === null, 403, 'No doctor profile is linked to this account.');

        return $doctor;
    }

    private function ensureDoctorOwnsAppointment(Doctor $doctor, Appointment $appointment): void
    {
        if ($appointment->doctor_id !== $doctor->id) {
            abort(404);
        }
    }

    public function index(Request $request): View
    {
        $doctor = $this->resolveDoctor($request);
        $statusFilter = $request->string('status')->toString();
        $search = trim($request->string('search')->toString());
        $fromDate = $request->string('from')->toString();
        $toDate = $request->string('to')->toString();

        $query = Appointment::with('patient:id,full_name,patient_code,avatar_tone')
            ->where('doctor_id', $doctor->id);

        if ($statusFilter !== '' && $statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('appointment_code', 'like', "%{$search}%")
                    ->orWhereHas('patient', fn ($p) => $p->where('full_name', 'like', "%{$search}%")->orWhere('patient_code', 'like', "%{$search}%"));
            });
        }

        if ($fromDate !== '') {
            $query->whereDate('appointment_date', '>=', $fromDate);
        }

        if ($toDate !== '') {
            $query->whereDate('appointment_date', '<=', $toDate);
        }

        $appointments = $query
            ->orderByDesc('appointment_date')
            ->orderBy('start_time')
            ->paginate(10)
            ->withQueryString();

        $statusCounts = Appointment::where('doctor_id', $doctor->id)
            ->select('status', DB::raw('COUNT(*) as aggregate'))
            ->groupBy('status')
            ->pluck('aggregate', 'status')
            ->all();

        return view('doctor.appointments.index', [
            'doctor' => $doctor,
            'appointments' => $appointments,
            'statuses' => AppointmentStatus::cases(),
            'statusCounts' => $statusCounts,
            'totalCount' => array_sum($statusCounts),
            'activeStatus' => $statusFilter !== '' ? $statusFilter : 'all',
            'search' => $search,
            'fromDate' => $fromDate,
            'toDate' => $toDate,
        ]);
    }

    public function show(Request $request, Appointment $appointment): View
    {
        $doctor = $this->resolveDoctor($request);
        $this->ensureDoctorOwnsAppointment($doctor, $appointment);

        $appointment->load([
            'patient',
            'doctor',
            'bookedBy:id,name,role',
        ]);

        return view('doctor.appointments.show', [
            'doctor' => $doctor,
            'appointment' => $appointment,
        ]);
    }

    public function updateStatus(Request $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $this->resolveDoctor($request);
        $this->ensureDoctorOwnsAppointment($doctor, $appointment);

        $validated = $request->validate([
            'status' => ['required', Rule::enum(AppointmentStatus::class)],
        ]);

        $nextStatus = AppointmentStatus::from($validated['status']);

        if ($nextStatus === AppointmentStatus::Completed) {
            $result = $this->workflow->confirmCompletion($appointment, $request->user());

            return redirect()
                ->route('doctor.appointments.show', $appointment)
                ->with('status', $result['message']);
        }

        $this->workflow->transitionStatus($appointment, $nextStatus, $request->user());

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('status', "Appointment status updated to {$nextStatus->label()}.");
    }

    public function updateNotes(UpdateClinicalNotesRequest $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $this->resolveDoctor($request);
        $this->ensureDoctorOwnsAppointment($doctor, $appointment);

        $isDraft = $request->boolean('is_draft');
        $hadNotes = ! empty($appointment->clinical_notes);

        $appointment->update([
            'clinical_notes' => $request->string('clinical_notes')->toString(),
            'notes_draft' => $isDraft,
        ]);

        $appointment->loadMissing('patient:id,full_name');

        AuditLog::record(
            $request->user(),
            $isDraft ? 'Saved clinical note draft' : 'Added consultation note',
            "Patient · {$appointment->patient->full_name}",
            $hadNotes ? 'Clinical note updated' : 'Empty → Clinical note added'
        );

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('status', $isDraft ? 'Consultation note draft saved.' : 'Consultation notes saved.');
    }

    public function cancel(CancelAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $doctor = $this->resolveDoctor($request);
        $this->ensureDoctorOwnsAppointment($doctor, $appointment);

        $this->workflow->cancel(
            $appointment,
            $request->string('cancellation_reason')->toString(),
            $request->input('cancellation_notes'),
            $request->user()
        );

        return redirect()
            ->route('doctor.appointments.show', $appointment)
            ->with('status', 'Appointment has been cancelled.');
    }
}
