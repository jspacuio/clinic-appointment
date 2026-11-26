<?php

namespace App\Http\Controllers\Patient\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Patient\StorePatientRegistrationRequest;
use App\Models\AuditLog;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the Patient Portal registration view.
     */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user !== null && $user->isPatient()) {
            return redirect()->route('patient.dashboard');
        }

        return view('patient.auth.register');
    }

    /**
     * Handle an incoming patient registration request.
     */
    public function store(StorePatientRegistrationRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        [$user, $patient] = DB::transaction(function () use ($validated): array {
            $createdUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => UserRole::Patient,
                'phone' => $validated['phone'],
                'is_active' => true,
                'email_verified_at' => now(),
                'last_active_at' => now(),
            ]);

            $existingPatient = Patient::where('email', $createdUser->email)
                ->whereNull('user_id')
                ->first();

            if ($existingPatient !== null) {
                $existingPatient->update([
                    'user_id' => $createdUser->id,
                    'full_name' => $validated['name'],
                    'phone' => $validated['phone'],
                    'date_of_birth' => $validated['date_of_birth'] ?? $existingPatient->date_of_birth,
                    'sex' => $validated['sex'],
                    'address' => $validated['address'] ?? $existingPatient->address,
                    'is_active' => true,
                ]);
                $patient = $existingPatient;
            } else {
                $tones = ['purple', 'blue', 'amber', 'teal', 'pink'];
                $patient = Patient::create([
                    'user_id' => $createdUser->id,
                    'patient_code' => Patient::generateNextCode(),
                    'full_name' => $validated['name'],
                    'date_of_birth' => $validated['date_of_birth'] ?? null,
                    'sex' => $validated['sex'],
                    'phone' => $validated['phone'],
                    'email' => $createdUser->email,
                    'address' => $validated['address'] ?? null,
                    'avatar_tone' => $tones[array_rand($tones)],
                    'is_active' => true,
                ]);
            }

            return [$createdUser, $patient];
        });

        AuditLog::record(
            $user,
            'Registered patient',
            "Patient · {$patient->full_name}",
            "Self-registered account ({$patient->patient_code})"
        );

        event(new Registered($user));

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()
            ->intended(route('patient.dashboard', absolute: false))
            ->with('status', "Welcome to ClinicCare, {$patient->full_name}! You can now book an appointment.");
    }
}
