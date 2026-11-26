<?php

namespace App\Http\Controllers\Doctor;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    private function resolveDoctor(Request $request): Doctor
    {
        $doctor = $request->user()->doctor;
        abort_if($doctor === null, 403, 'No doctor profile is linked to this account.');

        return $doctor;
    }

    public function index(Request $request): View
    {
        $doctor = $this->resolveDoctor($request);
        $search = trim($request->string('search')->toString());
        $sex = $request->string('sex')->toString();

        $query = Patient::whereHas('appointments', fn ($q) => $q->where('doctor_id', $doctor->id));

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($sex !== '' && $sex !== 'all') {
            $query->where('sex', $sex);
        }

        $patients = $query->orderBy('full_name')->paginate(10)->withQueryString();

        return view('doctor.patients.index', [
            'doctor' => $doctor,
            'patients' => $patients,
            'search' => $search,
            'sex' => $sex,
        ]);
    }

    public function show(Request $request, Patient $patient): View
    {
        $doctor = $this->resolveDoctor($request);

        $hasRelationship = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->exists();

        abort_unless($hasRelationship, 404);

        $visits = $patient->appointments()
            ->where('doctor_id', $doctor->id)
            ->with('doctor:id,full_name,specialization')
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get();

        return view('doctor.patients.show', [
            'doctor' => $doctor,
            'patient' => $patient,
            'visits' => $visits,
            'totalVisits' => $visits->count(),
            'upcomingVisits' => $visits->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])->count(),
            'noShowCount' => $visits->where('status', AppointmentStatus::NoShow)->count(),
        ]);
    }
}
