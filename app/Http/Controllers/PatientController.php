<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PatientController extends Controller
{
    public function index(Request $request): View
    {
        $query = Patient::query()->latest();

        if ($search = trim((string) $request->query('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('patient_code', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($sex = $request->query('sex')) {
            $query->where('sex', $sex);
        }

        if ($registeredDate = $request->query('registered_date')) {
            $query->whereDate('created_at', $registeredDate);
        }

        $patients = $query->paginate(10)->withQueryString();

        return view('clinic.patients', [
            'patients' => $patients,
            'filters' => [
                'search' => $search,
                'sex' => $sex,
                'registered_date' => $registeredDate,
            ],
        ]);
    }

    public function store(StorePatientRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['patient_code'] = Patient::generateCode();
        $data['status'] = 'Active';

        $patient = Patient::create($data);

        if ($request->input('redirect_to') === 'book') {
            return redirect()
                ->route('clinic.book', ['patient_id' => $patient->id])
                ->with('status', "Patient {$patient->full_name} ({$patient->patient_code}) was added and selected.");
        }

        return redirect()
            ->route('clinic.patients')
            ->with('status', "Patient {$patient->full_name} ({$patient->patient_code}) was added.");
    }

    public function show(Patient $patient): View
    {
        $patient->load('appointments');

        return view('clinic.patient-show', [
            'patient' => $patient,
        ]);
    }

    public function update(UpdatePatientRequest $request, Patient $patient): RedirectResponse
    {
        $patient->update($request->validated());

        return redirect()
            ->route('clinic.patients.show', $patient)
            ->with('status', "Patient {$patient->full_name} was updated.");
    }

    public function destroy(Patient $patient): RedirectResponse
    {
        $name = $patient->full_name;
        $patient->delete();

        return redirect()
            ->route('clinic.patients')
            ->with('status', "Patient {$name} was removed.");
    }
}
