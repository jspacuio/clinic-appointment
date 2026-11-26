@php
    $clinicRole = session('clinic_role', 'Admin');
    $adminNav = [
        ['dashboard', 'grid', 'Appointment Dashboard', 'dashboard'],
        ['clinic.book', 'plus', 'Book Appointment', 'clinic.book'],
        ['clinic.appointments', 'clock', 'Appointments', 'clinic.appointments*'],
        ['clinic.schedules', 'calendar', 'Doctor Schedules', 'clinic.schedules'],
        ['clinic.patients', 'users', 'Patients', 'clinic.patients'],
        ['clinic.doctors', 'doctor', 'Doctors', 'clinic.doctors'],
        ['clinic.reports', 'report', 'Reports', 'clinic.reports'],
        ['clinic.users', 'shield', 'User Management', 'clinic.users'],
        ['clinic.audit', 'log', 'Audit Trail', 'clinic.audit'],
    ];
    $doctorNav = [
        ['dashboard', 'grid', 'Today’s Overview', 'dashboard'],
        ['clinic.appointments', 'clock', 'My Appointments', 'clinic.appointments*'],
        ['clinic.schedules', 'calendar', 'My Schedule', 'clinic.schedules'],
        ['clinic.patients', 'users', 'My Patients', 'clinic.patients'],
    ];
    $navItems = $clinicRole === 'Admin' ? $adminNav : $doctorNav;
@endphp

<div class="sidebar-scrim" x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"></div>

<aside class="sidebar" :class="{ 'open': sidebarOpen }">
    <div class="side-head">
        <a href="{{ route('dashboard') }}" class="logo">
            <div class="logo-mark"><span></span><span></span></div>
            <div>
                <strong>ClinicCare</strong>
                <small>Appointment system</small>
            </div>
        </a>
        <button type="button" class="icon-btn mobile-only" @click="sidebarOpen = false" aria-label="Close menu">
            <x-clinic-icon name="close" />
        </button>
    </div>

    <div class="role-pill">
        <span>{{ $clinicRole === 'Admin' ? 'AR' : 'EM' }}</span>
        <div>
            <strong>{{ $clinicRole === 'Admin' ? 'Admin Portal' : 'Doctor Portal' }}</strong>
            <small>{{ $clinicRole === 'Admin' ? 'Reception & management' : 'Clinical workspace' }}</small>
        </div>
    </div>

    <nav>
        <small>WORKSPACE</small>
        @foreach ($navItems as [$routeName, $icon, $label, $pattern])
            <a href="{{ route($routeName) }}"
               class="{{ request()->routeIs($pattern) ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <x-clinic-icon :name="$icon" />
                <span>{{ $label }}</span>
                @if ($routeName === 'clinic.appointments')
                    <b>8</b>
                @endif
            </a>
        @endforeach
    </nav>

    <div class="side-bottom">
        <a href="{{ route('clinic.style') }}" class="{{ request()->routeIs('clinic.style') ? 'active' : '' }}">
            <x-clinic-icon name="edit" />
            <span>Style Guide</span>
        </a>
        @auth
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">
                    <x-clinic-icon name="logout" />
                    <span>Sign out</span>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}">
                <x-clinic-icon name="logout" />
                <span>Sign in</span>
            </a>
        @endauth
    </div>
</aside>
