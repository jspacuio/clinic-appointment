<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePatientRequest;
use App\Http\Requests\Admin\UpdatePatientRequest;
use App\Models\AuditLog;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $sex = $request->string('sex')->toString();
        $registeredDate = $request->string('registered_date')->toString();

        $query = Patient::query();

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

        if ($registeredDate !== '') {
            $query->whereDate('created_at', $registeredDate);
        }

        $patients = $query->orderByDesc('id')->paginate(10)->withQueryString();

        return view('admin.patients.index', [
            'patients' => $patients,
            'search' => $search,
            'sex' => $sex,
            'registeredDate' => $registeredDate,
        ]);
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $tones = ['purple', 'blue', 'amber', 'teal', 'pink'];
        $validated = $request->validated();

        $patient = Patient::create([
            'patient_code' => Patient::generateNextCode(),
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'sex' => $validated['sex'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'address' => $validated['address'] ?? null,
            'allergy_note' => $validated['allergy_note'] ?? null,
            'allergy_recorded_at' => ! empty($validated['allergy_note']) ? now()->toDateString() : null,
            'avatar_tone' => $tones[array_rand($tones)],
            'is_active' => true,
        ]);

        AuditLog::record(
            $request->user(),
            'Created patient',
            "Patient · {$patient->full_name}",
            "New record {$patient->patient_code} created"
        );

        if ($request->input('redirect_to') === 'book') {
            return redirect()
                ->route('admin.appointments.create', ['patient_id' => $patient->id])
                ->with('status', "Patient {$patient->full_name} registered.");
        }

        return redirect()
            ->route('admin.patients.index')
            ->with('status', "Patient {$patient->full_name} ({$patient->patient_code}) created.");
    }

    public function show(Patient $patient): View
    {
        $visits = $patient->appointments()
            ->with('doctor:id,full_name,specialization')
            ->orderByDesc('appointment_date')
            ->orderByDesc('start_time')
            ->get();

        $totalVisits = $visits->count();
        $upcomingVisits = $visits->whereIn('status', [AppointmentStatus::Pending, AppointmentStatus::Confirmed])->count();
        $noShowCount = $visits->where('status', AppointmentStatus::NoShow)->count();

        return view('admin.patients.show', [
            'patient' => $patient,
            'visits' => $visits,
            'totalVisits' => $totalVisits,
            'upcomingVisits' => $upcomingVisits,
            'noShowCount' => $noShowCount,
        ]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $validated = $request->validated();
        $allergyNote = $validated['allergy_note'] ?? null;

        $patient->update([
            'full_name' => $validated['full_name'],
            'date_of_birth' => $validated['date_of_birth'] ?? null,
            'sex' => $validated['sex'],
            'phone' => $validated['phone'],
            'email' => $validated['email'] ?? null,
            'emergency_contact' => $validated['emergency_contact'] ?? null,
            'address' => $validated['address'] ?? null,
            'allergy_note' => $allergyNote,
            'allergy_recorded_at' => $allergyNote !== null && $allergyNote !== $patient->allergy_note
                ? now()->toDateString()
                : $patient->allergy_recorded_at,
            'is_active' => $request->boolean('is_active', true),
        ]);

        AuditLog::record(
            $request->user(),
            'Updated patient',
            "Patient · {$patient->full_name}",
            "Updated profile {$patient->patient_code}"
        );

        return redirect()
            ->route('admin.patients.show', $patient)
            ->with('status', 'Patient profile updated.');
    }
}
