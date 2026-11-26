@php
    $user = auth()->user();
    $doctorProfile = $user->doctor;
    $doctorPendingCount = $doctorProfile !== null
        ? \App\Models\Appointment::where('doctor_id', $doctorProfile->id)
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
    <title>@yield('title', 'Doctor Portal') · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<div class="app-shell">
    <div id="sidebar-scrim" class="sidebar-scrim" hidden onclick="toggleSidebar(false)"></div>
    <aside id="app-sidebar" class="sidebar doctor-theme">
        <div class="side-head">
            <a href="{{ route('doctor.dashboard') }}"><x-clinic-logo /></a>
            <button type="button" class="icon-btn mobile-only" onclick="toggleSidebar(false)" aria-label="Close menu">
                <x-clinic-icon name="close" />
            </button>
        </div>

        <div class="role-pill">
            <span>{{ $doctorProfile?->initials() ?? $user->initials() }}</span>
            <div>
                <strong>Doctor Portal</strong>
                <small>{{ $doctorProfile?->specialization ?? 'Clinical workspace' }}</small>
            </div>
        </div>

        <nav>
            <small>CLINICAL WORKSPACE</small>
            <a href="{{ route('doctor.dashboard') }}" class="{{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                <x-clinic-icon name="grid" /><span>Today’s Overview</span>
            </a>
            <a href="{{ route('doctor.appointments.index') }}" class="{{ request()->routeIs('doctor.appointments.*') ? 'active' : '' }}">
                <x-clinic-icon name="clock" /><span>My Appointments</span>
                @if ($doctorPendingCount > 0)
                    <b>{{ $doctorPendingCount }}</b>
                @endif
            </a>
            <a href="{{ route('doctor.schedules.index') }}" class="{{ request()->routeIs('doctor.schedules.*') ? 'active' : '' }}">
                <x-clinic-icon name="calendar" /><span>My Schedule</span>
            </a>
            <a href="{{ route('doctor.patients.index') }}" class="{{ request()->routeIs('doctor.patients.*') ? 'active' : '' }}">
                <x-clinic-icon name="users" /><span>My Patients</span>
            </a>
        </nav>

        <div class="side-bottom">
            <form method="POST" action="{{ route('doctor.logout') }}">
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
                <h1>@yield('topbar-title', 'Today’s Overview')</h1>
                <p>@yield('topbar-subtitle', now()->format('l, F j, Y'))</p>
            </div>
            <div class="top-actions">
                <span class="portal-badge doctor"><x-clinic-icon name="doctor" size="14" />Doctor Portal</span>
                <a href="{{ route('doctor.appointments.index') }}" class="icon-btn notification" aria-label="Upcoming appointments">
                    <x-clinic-icon name="bell" />
                    @if ($doctorPendingCount > 0)
                        <i></i>
                    @endif
                </a>
                <div class="profile">
                    <x-clinic-avatar :name="$doctorProfile?->full_name ?? $user->name" :tone="$doctorProfile?->avatar_tone ?? 'teal'" />
                    <div>
                        <strong>{{ $doctorProfile?->full_name ?? $user->name }}</strong>
                        <small>{{ $doctorProfile?->specialization ?? 'Doctor' }}</small>
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
