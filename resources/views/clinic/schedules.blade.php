@extends('layouts.master')

@php
    $clinicRole = session('clinic_role', 'Admin');
    $weekStart = \Carbon\Carbon::parse(request('week', now()->startOfWeek()->format('Y-m-d')))->startOfWeek();
    $prevWeek = $weekStart->copy()->subWeek()->format('Y-m-d');
    $nextWeek = $weekStart->copy()->addWeek()->format('Y-m-d');
    $todayWeek = now()->startOfWeek()->format('Y-m-d');
    $weekEnd = $weekStart->copy()->addDays(4);
    $weekRange = $weekStart->format('M j') . '–' . $weekEnd->format('j, Y');
    $weekHeading = $weekStart->format('F j') . '–' . $weekEnd->format('j, Y');

    $times = ['8:00', '9:00', '10:00', '11:00', '12:00', '1:00', '2:00', '3:00', '4:00'];
    $days = collect(range(0, 4))->map(fn ($offset) => $weekStart->copy()->addDays($offset));
@endphp

@section('title', ($clinicRole === 'Doctor' ? 'My Schedule' : 'Doctor Schedules') . ' — ClinicCare')
@section('page-title', $clinicRole === 'Doctor' ? 'My Schedule' : 'Doctor Schedules')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div class="page-header">
    <div>
        <h2>{{ $clinicRole === 'Doctor' ? 'My Schedule' : 'Doctor Schedules' }}</h2>
        <p>Set available hours and manage blocked or booked times.</p>
    </div>
    <div class="header-actions">
        <a href="{{ route('clinic.schedules', ['week' => $todayWeek]) }}" class="btn secondary">{{ $weekRange }}</a>
        <a href="{{ route('clinic.book') }}" class="btn primary"><x-clinic-icon name="plus" :size="17" /><span>Add availability</span></a>
    </div>
</div>

@if ($clinicRole === 'Admin')
    <section class="card schedule-filter">
        <label class="field">
            <span>Doctor</span>
            <select>
                <option>Dr. Elena Morris</option>
                <option>Dr. James Chen</option>
            </select>
        </label>
        <div class="doctor-summary">
            <span class="avatar teal md">EM</span>
            <span><strong>Dr. Elena Morris</strong><small>General Medicine · 30 min slots</small></span>
        </div>
        <div class="schedule-legend">
            <span><i class="available"></i>Available</span>
            <span><i class="booked"></i>Booked</span>
            <span><i class="blocked"></i>Blocked</span>
        </div>
    </section>
@endif

<section class="card calendar-card">
    <div class="calendar-nav">
        <a href="{{ route('clinic.schedules', ['week' => $prevWeek]) }}" class="icon-btn" aria-label="Previous week">‹</a>
        <div>
            <h3>{{ $weekHeading }}</h3>
            <p>Weekly availability</p>
        </div>
        <a href="{{ route('clinic.schedules', ['week' => $nextWeek]) }}" class="icon-btn" aria-label="Next week">›</a>
        <a href="{{ route('clinic.schedules', ['week' => $todayWeek]) }}" class="btn secondary">Today</a>
    </div>

    <div class="weekly-grid">
        <div class="week-head empty"></div>
        @foreach ($days as $day)
            <div class="week-head {{ $day->isToday() ? 'today' : '' }}">
                <strong>{{ $day->format('D') }}</strong>
                <span>{{ $day->format('j') }}</span>
            </div>
        @endforeach

        @foreach ($times as $row => $t)
            <div class="time-label">{{ $t }} {{ $row < 4 ? 'AM' : 'PM' }}</div>
            @foreach ($days as $col => $day)
                @php
                    $isBooked = ($row === 1 && $col === 0) || ($row === 2 && $col === 2) || ($row === 5 && $col === 1);
                    $isBlocked = ($row === 4) || ($row === 7 && $col === 3);
                    $state = $isBooked ? 'booked' : ($isBlocked ? 'blocked' : 'available');
                    $bookedNames = ['S. Carter', 'L. Wilson', 'O. Martinez'];
                @endphp
                <button type="button" class="slot {{ $state }}">
                    <strong>{{ $state === 'booked' ? $bookedNames[$col % 3] : ($state === 'blocked' ? 'Blocked' : 'Available') }}</strong>
                    @if ($state === 'booked')
                        <small>{{ $t }}</small>
                    @endif
                </button>
            @endforeach
        @endforeach
    </div>
</section>
@endsection
