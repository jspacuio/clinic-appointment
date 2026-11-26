<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Portal Sign In · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<main class="login-page">
    <section class="login-brand">
        <div class="brand-inner">
            <a href="{{ route('home') }}"><x-clinic-logo /></a>
            <div class="brand-copy">
                <span class="eyebrow light">ADMINISTRATION &amp; RECEPTION</span>
                <h1>Every appointment, clearly coordinated.</h1>
                <p>Manage patient registration, doctor availability, booking conflicts, clinic reports, and user access from the Admin Portal.</p>
                <div class="brand-features">
                    <span><i><x-clinic-icon name="check" size="14" /></i>Book without schedule conflicts</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Manage doctors, patients, and clinic schedules</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Monitor reports and full audit logs</span>
                </div>
            </div>
            <div class="trust-note">
                <x-clinic-icon name="shield" />
                <span>
                    <strong>Controlled &amp; auditable</strong>
                    <small>Every appointment change is tracked securely</small>
                </span>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <form class="login-form" method="POST" action="{{ route('admin.login.store') }}">
            @csrf
            <div class="mobile-login-logo"><x-clinic-logo /></div>
            <span class="eyebrow">ADMIN PORTAL</span>
            <h2>Administrator Sign In</h2>
            <p>Sign in to manage today’s patients, schedules, and appointments.</p>

            @if ($errors->any())
                <div class="error-banner">
                    <x-clinic-icon name="alert" size="18" />
                    <span>
                        <strong>Unable to sign in</strong>
                        {{ $errors->first() }}
                    </span>
                </div>
            @endif

            <label class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                <span>Email address</span>
                <input type="email" name="email" value="{{ old('email', 'admin@cliniccare.com') }}" placeholder="admin@cliniccare.com" required autofocus>
            </label>

            <label class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                <span>Password</span>
                <input type="password" name="password" value="ClinicCare2025" required>
            </label>

            <div class="form-options">
                <label><input type="checkbox" name="remember" checked> Remember me</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn primary">Sign in to Admin Portal</button>
            <small class="demo-hint">Demo Admin: admin@cliniccare.com / ClinicCare2025</small>

            <footer>
                Are you a practitioner? <a href="{{ route('doctor.login') }}">Go to Doctor Portal &rarr;</a>
            </footer>
        </form>
    </section>
</main>
</body>
</html>
