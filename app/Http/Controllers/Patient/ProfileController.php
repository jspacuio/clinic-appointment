<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
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

        return view('patient.profile.edit', [
            'user' => $user,
            'patient' => $patient,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();
        $patient = $user->patient;

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'date_of_birth' => ['nullable', 'date', 'before:today'],
            'sex' => ['required', Rule::in(['Female', 'Male', 'Prefer not to say'])],
            'emergency_contact' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:500'],
            'allergy_note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::transaction(function () use ($user, $patient, $validated): void {
            $user->update([
                'name' => $validated['full_name'],
                'phone' => $validated['phone'],
            ]);

            if ($patient !== null) {
                $allergyNote = $validated['allergy_note'] ?? null;

                $patient->update([
                    'full_name' => $validated['full_name'],
                    'phone' => $validated['phone'],
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'sex' => $validated['sex'],
                    'emergency_contact' => $validated['emergency_contact'] ?? null,
                    'address' => $validated['address'] ?? null,
                    'allergy_note' => $allergyNote,
                    'allergy_recorded_at' => $allergyNote !== null && $allergyNote !== $patient->allergy_note
                        ? now()->toDateString()
                        : $patient->allergy_recorded_at,
                ]);
            }
        });

        return redirect()
            ->route('patient.profile.edit')
            ->with('status', 'Your profile details have been updated.');
    }
}
