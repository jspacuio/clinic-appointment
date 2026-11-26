@php
    $user = auth()->user();
    $patientProfile = $user->patient;
    $upcomingBadgeCount = $patientProfile !== null
        ? \App\Models\Appointment::where('patient_id', $patientProfile->id)
            ->whereDate('appointment_date', '>=', now()->toDateString())
            ->whereIn('status', [\App\Enums\AppointmentStatus::Pending, \App\Enums\AppointmentStatus::Confirmed])
            ->count()
        : 0;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Patient Portal') · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<div class="app-shell">
    <div id="sidebar-scrim" class="sidebar-scrim" hidden onclick="toggleSidebar(false)"></div>
    <aside id="app-sidebar" class="sidebar patient-theme">
        <div class="side-head">
            <a href="{{ route('patient.dashboard') }}"><x-clinic-logo /></a>
            <button type="button" class="icon-btn mobile-only" onclick="toggleSidebar(false)" aria-label="Close menu">
                <x-clinic-icon name="close" />
            </button>
        </div>

        <div class="role-pill">
            <span>{{ $patientProfile?->initials() ?? $user->initials() }}</span>
            <div>
                <strong>Patient Portal</strong>
                <small>{{ $patientProfile?->patient_code ?? 'ClinicCare member' }}</small>
            </div>
        </div>

        <nav>
            <small>MY HEALTH</small>
            <a href="{{ route('patient.dashboard') }}" class="{{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                <x-clinic-icon name="grid" /><span>Overview</span>
            </a>
            <a href="{{ route('patient.appointments.create') }}" class="{{ request()->routeIs('patient.appointments.create') ? 'active' : '' }}">
                <x-clinic-icon name="plus" /><span>Book Appointment</span>
            </a>
            <a href="{{ route('patient.appointments.index') }}" class="{{ request()->routeIs('patient.appointments.index', 'patient.appointments.show') ? 'active' : '' }}">
                <x-clinic-icon name="calendar" /><span>My Appointments</span>
                @if ($upcomingBadgeCount > 0)
                    <b>{{ $upcomingBadgeCount }}</b>
                @endif
            </a>
            <a href="{{ route('patient.profile.edit') }}" class="{{ request()->routeIs('patient.profile.*') ? 'active' : '' }}">
                <x-clinic-icon name="patient" /><span>My Profile</span>
            </a>
        </nav>

        <div class="side-bottom">
            <form method="POST" action="{{ route('patient.logout') }}">
                @csrf
                <button type="submit">
                    <x-clinic-icon name="logout" /><span>Sign out</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="app-main">
        <header class="topbar">
            <button type="button" class="icon-btn mobile-only" onclick="toggleSidebar(true)" aria-label="Open menu">
                <x-clinic-icon name="menu" />
            </button>
            <div class="title">
                <h1>@yield('topbar-title', 'Patient Overview')</h1>
                <p>@yield('topbar-subtitle', now()->format('l, F j, Y'))</p>
            </div>
            <div class="top-actions">
                <a href="{{ route('home') }}" class="portal-badge" style="background:#eff6ff; color:#1d4ed8; border-color:#dbeafe;">Public site</a>
                <span class="portal-badge patient"><x-clinic-icon name="patient" size="14" />Patient Portal</span>
                <a href="{{ route('patient.appointments.index') }}" class="icon-btn notification" aria-label="Upcoming appointments">
                    <x-clinic-icon name="bell" />
                    @if ($upcomingBadgeCount > 0)
                        <i></i>
                    @endif
                </a>
                <div class="profile">
                    <x-clinic-avatar :name="$patientProfile?->full_name ?? $user->name" :tone="$patientProfile?->avatar_tone ?? 'teal'" />
                    <div>
                        <strong>{{ $patientProfile?->full_name ?? $user->name }}</strong>
                        <small>{{ $patientProfile?->patient_code ?? 'Patient' }}</small>
                    </div>
                </div>
            </div>
        </header>

        <main class="content">
            @if (session('status'))
                <div class="flash-toast">
                    <div class="toast-icon"><x-clinic-icon name="check" size="16" /></div>
                    <span><strong>{{ session('status') }}</strong></span>
                </div>
            @endif

            @if (session('error'))
                <div class="flash-toast error">
                    <x-clinic-icon name="alert" size="18" />
                    <span><strong>{{ session('error') }}</strong></span>
                </div>
            @endif

            @if ($errors->any())
                <div class="flash-toast error">
                    <x-clinic-icon name="alert" size="18" />
                    <span><strong>Please fix the highlighted errors:</strong> {{ $errors->first() }}</span>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>

<script>
    function toggleSidebar(open) {
        const sidebar = document.getElementById('app-sidebar');
        const scrim = document.getElementById('sidebar-scrim');
        if (!sidebar || !scrim) return;
        sidebar.classList.toggle('open', open);
        scrim.hidden = !open;
    }

    function openModal(id) {
        const el = document.getElementById(id);
        if (el) el.hidden = false;
    }

    function closeModal(id) {
        const el = document.getElementById(id);
        if (el) el.hidden = true;
    }
</script>
@stack('scripts')
</body>
</html>
