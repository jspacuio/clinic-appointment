<?php

namespace App\Http\Controllers\Doctor\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the Doctor Portal login view.
     */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user !== null && $user->isDoctor()) {
            return redirect()->route('doctor.dashboard');
        }

        return view('doctor.auth.login');
    }

    /**
     * Handle an incoming Doctor Portal authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        if ($user === null || ! $user->isDoctor()) {
            $isPatient = $user?->isPatient() ?? false;
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => $isPatient
                    ? 'Patient accounts must sign in through the Patient Portal.'
                    : 'Administrator accounts must sign in through the Admin Portal.',
            ]);
        }

        $doctor = $user->doctor;

        if (! $user->is_active || $doctor === null || ! $doctor->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your doctor profile is currently inactive. Contact an administrator.',
            ]);
        }

        $user->forceFill(['last_active_at' => now()])->save();
        $request->session()->regenerate();

        return redirect()->intended(route('doctor.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated Doctor Portal session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('doctor.login');
    }
}
