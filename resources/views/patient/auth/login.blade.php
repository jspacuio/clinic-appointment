<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Sign In · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<main class="login-page">
    <section class="login-brand patient-brand">
        <div class="brand-inner">
            <a href="{{ route('home') }}"><x-clinic-logo /></a>
            <div class="brand-copy">
                <span class="eyebrow light">PATIENT SELF-SERVICE PORTAL</span>
                <h1>Book your visit with the right specialist.</h1>
                <p>Browse available clinic categories—from Eye Care and General Medicine to Pediatrics and Dermatology—choose your doctor, and reserve an open time slot.</p>
                <div class="brand-features">
                    <span><i><x-clinic-icon name="check" size="14" /></i>Pick any category &amp; specialist doctor</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>See real-time open slots with zero conflicts</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Track or cancel your upcoming visits anytime</span>
                </div>
            </div>
            <div class="trust-note">
                <x-clinic-icon name="shield" />
                <span>
                    <strong>New to ClinicCare?</strong>
                    <small>Register for free in under a minute to book your appointment</small>
                </span>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <form class="login-form" method="POST" action="{{ route('patient.login.store') }}">
            @csrf
            <div class="mobile-login-logo"><x-clinic-logo /></div>
            <span class="eyebrow">PATIENT PORTAL</span>
            <h2>Patient Sign In</h2>
            <p>Sign in to book appointments or view your upcoming clinic visits.</p>

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
                <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required autofocus>
            </label>

            <label class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                <span>Password</span>
                <input type="password" name="password" placeholder="Enter your password" required>
            </label>

            <div class="form-options">
                <label><input type="checkbox" name="remember" checked> Remember me</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}">Forgot password?</a>
                @endif
            </div>

            <button type="submit" class="btn primary">Sign in to Patient Portal</button>
            <small class="demo-hint">Demo Patient: sophia.carter@email.com / ClinicCare2025</small>

            <footer>
                Don’t have an account yet? <a href="{{ route('patient.register') }}">Register as a Patient &rarr;</a>
                <div style="margin-top:8px;">
                    <a href="{{ route('home') }}">&larr; Back to Portal Selection</a>
                </div>
            </footer>
        </form>
    </section>
</main>
</body>
</html>
