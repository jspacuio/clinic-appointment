@php
    $clinicRole = session('clinic_role', 'Admin');
    $userName = auth()->user()->name ?? ($clinicRole === 'Admin' ? 'Anna Reyes' : 'Dr. Elena Morris');
    $initials = collect(explode(' ', str_replace('Dr. ', '', $userName)))
        ->filter()
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');
@endphp

<header class="topbar">
    <button type="button" class="icon-btn mobile-only" @click="sidebarOpen = true" aria-label="Open menu">
        <x-clinic-icon name="menu" />
    </button>

    <div class="title">
        <h1>@yield('page-title', 'Appointment Dashboard')</h1>
        <p>@yield('page-subtitle', 'ClinicCare administration')</p>
    </div>

    <div class="top-actions">
        <div class="role-switch" aria-label="Switch role">
            <a href="{{ route('clinic.role', 'Admin') }}" class="{{ $clinicRole === 'Admin' ? 'selected' : '' }}">Admin</a>
            <a href="{{ route('clinic.role', 'Doctor') }}" class="{{ $clinicRole === 'Doctor' ? 'selected' : '' }}">Doctor</a>
        </div>

        <button type="button" class="icon-btn notification" aria-label="Notifications">
            <x-clinic-icon name="bell" />
            <i></i>
        </button>

        <a href="{{ route('profile.edit') }}" class="profile">
            <span class="avatar {{ $clinicRole === 'Admin' ? 'blue' : 'teal' }} md" aria-label="{{ $userName }}">
                {{ $initials ?: ($clinicRole === 'Admin' ? 'AR' : 'EM') }}
            </span>
            <div>
                <strong>{{ $userName }}</strong>
                <small>{{ $clinicRole === 'Admin' ? 'Administrator' : 'General Medicine' }}</small>
            </div>
        </a>
    </div>
</header>
