<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sign in — ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/cliniccare.css') }}">
</head>
<body>
    <main class="login-page">
        <section class="login-brand">
            <div class="brand-inner">
                <div class="logo">
                    <div class="logo-mark"><span></span><span></span></div>
                    <div>
                        <strong>ClinicCare</strong>
                        <small>Appointment system</small>
                    </div>
                </div>

                <div class="brand-copy">
                    <span class="eyebrow light">CLINIC APPOINTMENT MANAGEMENT</span>
                    <h1>Every appointment, clearly coordinated.</h1>
                    <p>Manage patient registration, doctor availability, booking conflicts, and visit status in one focused workspace.</p>
                    <div class="brand-features">
                        <span><i><x-clinic-icon name="check" :size="15" /></i>Book without schedule conflicts</span>
                        <span><i><x-clinic-icon name="check" :size="15" /></i>Track every visit from pending to completed</span>
                        <span><i><x-clinic-icon name="check" :size="15" /></i>Give administrators and doctors the right access</span>
                    </div>
                </div>

                <div class="trust-note">
                    <x-clinic-icon name="shield" />
                    <span>
                        <strong>Controlled & auditable</strong>
                        <small>Every appointment change is tracked securely</small>
                    </span>
                </div>
            </div>
        </section>

        <section class="login-panel">
            <form class="login-form" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mobile-login-logo">
                    <div class="logo">
                        <div class="logo-mark"><span></span><span></span></div>
                        <div>
                            <strong>ClinicCare</strong>
                            <small>Appointment system</small>
                        </div>
                    </div>
                </div>

                <span class="eyebrow">WELCOME BACK</span>
                <h2>Clinic Appointment System</h2>
                <p>Sign in to manage today’s patients, schedules, and appointments.</p>

                @if (session('status'))
                    <div class="info-banner" style="margin-bottom: 14px;">
                        <x-clinic-icon name="check" />
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="error-banner" style="margin-bottom: 14px;">
                        <x-clinic-icon name="alert" />
                        <span>
                            <strong>Unable to sign in</strong>
                            {{ $errors->first() }}
                        </span>
                    </div>
                @endif

                <label class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                    <span>Email address</span>
                    <input type="email"
                           name="email"
                           value="{{ old('email') }}"
                           placeholder="you@cliniccare.com"
                           required
                           autofocus
                           autocomplete="username">
                </label>

                <label class="field {{ $errors->has('password') ? 'has-error' : '' }}">
                    <span>Password</span>
                    <input type="password"
                           name="password"
                           required
                           autocomplete="current-password">
                </label>

                <div class="form-options">
                    <label>
                        <input type="checkbox" name="remember">
                        Remember me
                    </label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: var(--primary-dark); font-size: 10px; font-weight: 600;">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="btn primary">
                    <span>Sign in</span>
                </button>

                <footer>
                    Need help? <a href="{{ Route::has('register') ? route('register') : '#' }}" style="color: var(--primary-dark); font-weight: 600;">Contact your administrator</a>
                </footer>
            </form>
        </section>
    </main>
</body>
</html>
