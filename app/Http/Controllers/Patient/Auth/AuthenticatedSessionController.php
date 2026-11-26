<?php

namespace App\Http\Controllers\Patient\Auth;

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
     * Display the Patient Portal login view.
     */
    public function create(): View|RedirectResponse
    {
        $user = Auth::user();

        if ($user !== null && $user->isPatient()) {
            return redirect()->route('patient.dashboard');
        }

        return view('patient.auth.login');
    }

    /**
     * Handle an incoming Patient Portal authentication request.
     *
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = $request->user();

        if ($user === null || ! $user->isPatient()) {
            $isDoctor = $user?->isDoctor() ?? false;
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => $isDoctor
                    ? 'Doctor accounts must sign in through the Doctor Portal.'
                    : 'Administrator accounts must sign in through the Admin Portal.',
            ]);
        }

        if (! $user->is_active) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => 'Your patient account is currently inactive. Contact the clinic.',
            ]);
        }

        $user->forceFill(['last_active_at' => now()])->save();
        $request->session()->regenerate();

        return redirect()->intended(route('patient.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated Patient Portal session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('patient.login');
    }
}
