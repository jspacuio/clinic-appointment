<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = DB::transaction(function () use ($request): User {
            $created = User::create([
                'name' => $request->string('name')->toString(),
                'email' => $request->string('email')->toString(),
                'password' => Hash::make($request->string('password')->toString()),
                'role' => UserRole::Patient,
                'phone' => $request->input('phone'),
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $existingPatient = Patient::where('email', $created->email)
                ->whereNull('user_id')
                ->first();

            if ($existingPatient !== null) {
                $existingPatient->update([
                    'user_id' => $created->id,
                    'full_name' => $created->name,
                    'phone' => $request->input('phone') ?: $existingPatient->phone,
                    'is_active' => true,
                ]);
            } else {
                $tones = ['purple', 'blue', 'amber', 'teal', 'pink'];
                Patient::create([
                    'user_id' => $created->id,
                    'patient_code' => Patient::generateNextCode(),
                    'full_name' => $created->name,
                    'sex' => 'Prefer not to say',
                    'phone' => $request->input('phone') ?: 'Not provided',
                    'email' => $created->email,
                    'avatar_tone' => $tones[array_rand($tones)],
                    'is_active' => true,
                ]);
            }

            return $created;
        });

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
