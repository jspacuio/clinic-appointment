<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if ($user === null) {
            abort(403);
        }

        if (! $user->is_active) {
            auth()->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $loginRoute = match (true) {
                $request->is('doctor*') => 'doctor.login',
                $request->is('patient*') => 'patient.login',
                default => 'admin.login',
            };

            return redirect()->route($loginRoute)->withErrors([
                'email' => 'Your account is currently inactive. Contact an administrator.',
            ]);
        }

        $userRoleValue = $user->role?->value;

        if ($userRoleValue === null || ! in_array($userRoleValue, $roles, true)) {
            abort(403, 'You do not have permission to access this portal.');
        }

        return $next($request);
    }
}
