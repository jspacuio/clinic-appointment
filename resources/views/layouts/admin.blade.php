@php
    $user = auth()->user();
    $pendingBadgeCount = \App\Models\Appointment::where('status', \App\Enums\AppointmentStatus::Pending)->count();
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal') · ClinicCare</title>
    <link rel="icon" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('css/cliniccare.css') }}">
</head>
<body>
<div class="app-shell">
    <div id="sidebar-scrim" class="sidebar-scrim" hidden onclick="toggleSidebar(false)"></div>
    <aside id="app-sidebar" class="sidebar">
        <div class="side-head">
            <a href="{{ route('admin.dashboard') }}"><x-clinic-logo /></a>
            <button type="button" class="icon-btn mobile-only" onclick="toggleSidebar(false)" aria-label="Close menu">
                <x-clinic-icon name="close" />
            </button>
        </div>

        <div class="role-pill">
            <span>{{ $user->initials() }}</span>
            <div>
                <strong>Admin Portal</strong>
                <small>Reception &amp; management</small>
            </div>
        </div>

        <nav>
            <small>WORKSPACE</small>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <x-clinic-icon name="grid" /><span>Appointment Dashboard</span>
            </a>
            <a href="{{ route('admin.appointments.create') }}" class="{{ request()->routeIs('admin.appointments.create') ? 'active' : '' }}">
                <x-clinic-icon name="plus" /><span>Book Appointment</span>
            </a>
            <a href="{{ route('admin.appointments.index') }}" class="{{ request()->routeIs('admin.appointments.index', 'admin.appointments.show') ? 'active' : '' }}">
                <x-clinic-icon name="clock" /><span>Appointments</span>
                @if ($pendingBadgeCount > 0)
                    <b>{{ $pendingBadgeCount }}</b>
                @endif
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="{{ request()->routeIs('admin.schedules.*') ? 'active' : '' }}">
                <x-clinic-icon name="calendar" /><span>Doctor Schedules</span>
            </a>
            <a href="{{ route('admin.patients.index') }}" class="{{ request()->routeIs('admin.patients.*') ? 'active' : '' }}">
                <x-clinic-icon name="users" /><span>Patients</span>
            </a>
            <a href="{{ route('admin.doctors.index') }}" class="{{ request()->routeIs('admin.doctors.*') ? 'active' : '' }}">
                <x-clinic-icon name="doctor" /><span>Doctors</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="{{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
                <x-clinic-icon name="report" /><span>Reports</span>
            </a>
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <x-clinic-icon name="shield" /><span>User Management</span>
            </a>
            <a href="{{ route('admin.audit.index') }}" class="{{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
                <x-clinic-icon name="log" /><span>Audit Trail</span>
            </a>
        </nav>

        <div class="side-bottom">
            <a href="{{ route('admin.style-guide') }}" class="{{ request()->routeIs('admin.style-guide') ? 'active' : '' }}">
                <x-clinic-icon name="edit" /><span>Style Guide</span>
            </a>
            <form method="POST" action="{{ route('admin.logout') }}">
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
                <h1>@yield('topbar-title', 'Appointment Dashboard')</h1>
                <p>@yield('topbar-subtitle', now()->format('l, F j, Y'))</p>
            </div>
            <div class="top-actions">
                <a href="{{ route('home') }}" class="portal-badge" style="background:#eff6ff; color:#1d4ed8; border-color:#dbeafe;">Public site</a>
                <span class="portal-badge"><x-clinic-icon name="shield" size="14" />Admin Portal</span>
                <a href="{{ route('admin.appointments.index', ['status' => 'pending']) }}" class="icon-btn notification" aria-label="Pending requests">
                    <x-clinic-icon name="bell" />
                    @if ($pendingBadgeCount > 0)
                        <i></i>
                    @endif
                </a>
                <div class="profile">
                    <x-clinic-avatar :name="$user->name" tone="blue" />
                    <div>
                        <strong>{{ $user->name }}</strong>
                        <small>{{ $user->role?->label() }}</small>
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
