<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Doctor Portal Sign In · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<main class="login-page">
    <section class="login-brand doctor-brand">
        <div class="brand-inner">
            <a href="{{ route('home') }}"><x-clinic-logo /></a>
            <div class="brand-copy">
                <span class="eyebrow light">CLINICAL PRACTITIONER WORKSPACE</span>
                <h1>Focused care for every scheduled patient.</h1>
                <p>Review today’s patient queue, update visit progress, record private clinical consultation notes, and manage your availability.</p>
                <div class="brand-features">
                    <span><i><x-clinic-icon name="check" size="14" /></i>Live daily timeline and next-patient card</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Private consultation notes visible only to you</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Manage your weekly availability and patient history</span>
                </div>
            </div>
            <div class="trust-note">
                <x-clinic-icon name="shield" />
                <span>
                    <strong>Clinical privacy enforced</strong>
                    <small>Doctor accounts only access their assigned patients and visits</small>
                </span>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <form class="login-form" method="POST" action="{{ route('doctor.login.store') }}">
            @csrf
            <div class="mobile-login-logo"><x-clinic-logo /></div>
            <span class="eyebrow">DOCTOR PORTAL</span>
            <h2>Practitioner Sign In</h2>
            <p>Sign in to open your daily clinical schedule and patient visits.</p>

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
                <span>Doctor email address</span>
                <input type="email" name="email" value="{{ old('email', 'elena@cliniccare.com') }}" placeholder="doctor@cliniccare.com" required autofocus>
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

            <button type="submit" class="btn primary">Sign in to Doctor Portal</button>
            <small class="demo-hint">Demo Doctor: elena@cliniccare.com / ClinicCare2025</small>

            <footer>
                Clinic administrator or receptionist? <a href="{{ route('admin.login') }}">Go to Admin Portal &rarr;</a>
            </footer>
        </form>
    </section>
</main>
</body>
</html>
