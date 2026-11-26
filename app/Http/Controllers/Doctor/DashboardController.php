<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $doctor = $request->user()->doctor;
        abort_if($doctor === null, 403, 'No doctor profile is linked to this account.');

        $today = CarbonImmutable::today();

        $todayAppointments = Appointment::with('patient')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $today)
            ->orderBy('start_time')
            ->get();

        $nextAppointment = $todayAppointments->first(
            fn (Appointment $appt): bool => in_array($appt->status, [
                AppointmentStatus::Confirmed,
                AppointmentStatus::InProgress,
                AppointmentStatus::Pending,
            ], true)
        );

        $lastVisitForNextPatient = null;
        if ($nextAppointment !== null) {
            $lastVisitForNextPatient = Appointment::where('patient_id', $nextAppointment->patient_id)
                ->where('id', '!=', $nextAppointment->id)
                ->where('status', AppointmentStatus::Completed)
                ->orderByDesc('appointment_date')
                ->first();
        }

        $pendingNotesCount = Appointment::where('doctor_id', $doctor->id)
            ->whereIn('status', [AppointmentStatus::InProgress, AppointmentStatus::Completed])
            ->where(function ($q): void {
                $q->whereNull('clinical_notes')
                    ->orWhere('clinical_notes', '')
                    ->orWhere('notes_draft', true);
            })
            ->count();

        $completedTodayCount = $todayAppointments->where('status', AppointmentStatus::Completed)->count();

        return view('doctor.dashboard', [
            'doctor' => $doctor,
            'todayAppointments' => $todayAppointments,
            'nextAppointment' => $nextAppointment,
            'lastVisitForNextPatient' => $lastVisitForNextPatient,
            'pendingNotesCount' => $pendingNotesCount,
            'completedTodayCount' => $completedTodayCount,
        ]);
    }
}
