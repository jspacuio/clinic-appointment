<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'ClinicCare · Appointment System')</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body class="pub-body">
<header class="pub-header">
    <div class="pub-header-inner">
        <a href="{{ route('home') }}"><x-clinic-logo /></a>

        <nav class="pub-nav" aria-label="Primary navigation">
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('public.doctors') }}" class="{{ request()->routeIs('public.doctors') ? 'active' : '' }}">Doctors</a>
            <a href="{{ route('public.services') }}" class="{{ request()->routeIs('public.services') ? 'active' : '' }}">Services</a>
            <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('patient.appointments.index') : route('patient.login') }}">My Appointments</a>
        </nav>

        <div class="pub-actions">
            <a href="{{ route('admin.login') }}" class="pub-staff-link">Admin portal</a>
            <a href="{{ route('doctor.login') }}" class="pub-staff-link">Doctor portal</a>
            @auth
                @if (auth()->user()->isPatient())
                    <a href="{{ route('patient.dashboard') }}" class="btn secondary">My Portal</a>
                    <a href="{{ route('patient.appointments.create') }}" class="btn primary">
                        <x-clinic-icon name="calendar" size="17" /><span>Book appointment</span>
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="btn primary">Go to Workspace</a>
                @endif
            @else
                <a href="{{ route('patient.login') }}" class="btn secondary">Sign in</a>
                <a href="{{ route('patient.appointments.create') }}" class="btn primary">
                    <x-clinic-icon name="calendar" size="17" /><span>Book appointment</span>
                </a>
            @endauth
        </div>

        <button type="button" class="icon-btn mobile-only" onclick="document.getElementById('pub-mobile-nav').toggleAttribute('hidden')" aria-label="Toggle menu">
            <x-clinic-icon name="menu" />
        </button>
    </div>

    <div id="pub-mobile-nav" class="pub-mobile-menu" hidden>
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('public.doctors') }}">Doctors</a>
        <a href="{{ route('public.services') }}">Services &amp; Categories</a>
        <a href="{{ route('patient.appointments.create') }}">Book appointment</a>
        @auth
            <a href="{{ route('dashboard') }}" style="color:#2563eb;">My Workspace</a>
        @else
            <a href="{{ route('patient.login') }}">Patient Sign in</a>
            <a href="{{ route('patient.register') }}">Create Patient Account</a>
        @endauth
        <a href="{{ route('admin.login') }}" style="border-top:1px solid #f1f5f9; margin-top:6px; padding-top:12px;">Admin Portal</a>
        <a href="{{ route('doctor.login') }}">Doctor Portal</a>
    </div>
</header>

@yield('content')

<footer class="pub-footer">
    <div class="pub-footer-grid">
        <div>
            <x-clinic-logo :light="true" />
            <p style="margin-top:16px; font-size:14px; line-height:1.65;">
                Modern, compassionate healthcare for our community. Book trusted clinicians across any specialty online.
            </p>
        </div>

        <div>
            <h3>Quick links</h3>
            <div class="pub-footer-links">
                <a href="{{ route('public.doctors') }}">Find a doctor</a>
                <a href="{{ route('public.services') }}">Clinic categories &amp; services</a>
                <a href="{{ route('patient.appointments.create') }}">Book appointment</a>
                <a href="{{ route('patient.register') }}">Create patient account</a>
            </div>
        </div>

        <div>
            <h3>Contact</h3>
            <div class="pub-footer-item"><x-clinic-icon name="phone" size="16" /><span>(555) 201-4400</span></div>
            <div class="pub-footer-item"><x-clinic-icon name="mail" size="16" /><span>care@cliniccare.com</span></div>
            <div class="pub-footer-item"><x-clinic-icon name="location" size="16" /><span>100 Health Ave, Brookfield</span></div>
        </div>

        <div>
            <h3>Clinic hours &amp; Portals</h3>
            <div class="pub-footer-links">
                <span>Mon–Fri: 8:00 AM – 5:00 PM</span>
                <span>Saturday: 9:00 AM – 2:00 PM</span>
                <a href="{{ route('admin.login') }}" style="color:#93c5fd; margin-top:6px; font-size:12px; font-weight:600;">Admin Portal Sign In &rarr;</a>
                <a href="{{ route('doctor.login') }}" style="color:#93c5fd; font-size:12px; font-weight:600;">Doctor Portal Sign In &rarr;</a>
            </div>
        </div>
    </div>

    <div class="pub-footer-bottom">
        &copy; {{ date('Y') }} ClinicCare Appointment System. All rights reserved.
    </div>
</footer>
</body>
</html>
