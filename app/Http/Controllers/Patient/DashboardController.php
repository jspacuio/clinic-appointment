<?php

namespace App\Http\Controllers\Patient;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
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

        $today = CarbonImmutable::today()->toDateString();

        $upcomingAppointments = Appointment::with('doctor:id,full_name,specialization,avatar_tone')
            ->where('patient_id', $patient->id)
            ->whereDate('appointment_date', '>=', $today)
            ->whereIn('status', [
                AppointmentStatus::Pending->value,
                AppointmentStatus::Confirmed->value,
                AppointmentStatus::InProgress->value,
            ])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $totalAppointments = Appointment::where('patient_id', $patient->id)->count();
        $upcomingCount = Appointment::where('patient_id', $patient->id)
            ->whereDate('appointment_date', '>=', $today)
            ->whereIn('status', [AppointmentStatus::Pending->value, AppointmentStatus::Confirmed->value])
            ->count();
        $completedCount = Appointment::where('patient_id', $patient->id)
            ->where('status', AppointmentStatus::Completed->value)
            ->count();

        $categories = Doctor::where('is_active', true)
            ->select('specialization', DB::raw('COUNT(*) as doctors_count'))
            ->groupBy('specialization')
            ->orderBy('specialization')
            ->get();

        return view('patient.dashboard', [
            'patient' => $patient,
            'upcomingAppointments' => $upcomingAppointments,
            'totalAppointments' => $totalAppointments,
            'upcomingCount' => $upcomingCount,
            'completedCount' => $completedCount,
            'categories' => $categories,
        ]);
    }
}
