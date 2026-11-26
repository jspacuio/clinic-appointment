<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Registration · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<main class="login-page">
    <section class="login-brand patient-brand">
        <div class="brand-inner">
            <a href="{{ route('home') }}"><x-clinic-logo /></a>
            <div class="brand-copy">
                <span class="eyebrow light">NEW PATIENT REGISTRATION</span>
                <h1>Your clinic appointments, made effortless.</h1>
                <p>Create your free ClinicCare patient account to choose a medical category, select a doctor, and schedule your consultation online.</p>
                <div class="brand-features">
                    <span><i><x-clinic-icon name="check" size="14" /></i>Instant clinic patient profile &amp; ID</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Choose Eye Care, General Medicine, Pediatrics, Dermatology &amp; more</span>
                    <span><i><x-clinic-icon name="check" size="14" /></i>Live appointment status updates</span>
                </div>
            </div>
            <div class="trust-note">
                <x-clinic-icon name="shield" />
                <span>
                    <strong>Private &amp; secure</strong>
                    <small>Your personal information is only shared with clinic staff</small>
                </span>
            </div>
        </div>
    </section>

    <section class="login-panel">
        <form class="login-form" style="width:min(440px,100%)" method="POST" action="{{ route('patient.register.store') }}">
            @csrf
            <div class="mobile-login-logo"><x-clinic-logo /></div>
            <span class="eyebrow">CREATE ACCOUNT</span>
            <h2>Patient Registration</h2>
            <p>Fill in your details below to register and book your appointment.</p>

            @if ($errors->any())
                <div class="error-banner">
                    <x-clinic-icon name="alert" size="18" />
                    <span>
                        <strong>Registration incomplete</strong>
                        {{ $errors->first() }}
                    </span>
                </div>
            @endif

            <div class="form-grid" style="margin-top:16px; gap:12px;">
                <label class="field full {{ $errors->has('name') ? 'has-error' : '' }}">
                    <span>Full name *</span>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="e.g. Alex Rivera" required autofocus>
                </label>

                <label class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <span>Email address *</span>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@email.com" required>
                </label>

                <label class="field {{ $errors->has('phone') ? 'has-error' : '' }}">
                    <span>Phone number *</span>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(555) 123-4567" required>
                </label>

                <label class="field {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                    <span>Date of birth</span>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                </label>

                <label class="field {{ $errors->has('sex') ? 'has-error' : '' }}">
                    <span>Sex *</span>
                    <select name="sex" required>
                        <option value="Female" @selected(old('sex') === 'Female')>Female</option>
                        <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                        <option value="Prefer not to say" @selected(old('sex', 'Prefer not to say') === 'Prefer not to say')>Prefer not to say</option>
                    </select>
                </label>

                <label class="field full {{ $errors->has('address') ? 'has-error' : '' }}">
                    <span>Address (optional)</span>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Street, city">
                </label>

                <label class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                    <span>Password *</span>
                    <input type="password" name="password" placeholder="Min. 8 characters" required>
                </label>

                <label class="field {{ $errors->has('password_confirmation') ? 'has-error' : '' }}">
                    <span>Confirm password *</span>
                    <input type="password" name="password_confirmation" placeholder="Repeat password" required>
                </label>
            </div>

            <button type="submit" class="btn primary">Create Account &amp; Continue</button>

            <footer>
                Already have a patient account? <a href="{{ route('patient.login') }}">Sign in &rarr;</a>
                <div style="margin-top:8px;">
                    <a href="{{ route('home') }}">&larr; Back to Portal Selection</a>
                </div>
            </footer>
        </form>
    </section>
</main>
</body>
</html>
