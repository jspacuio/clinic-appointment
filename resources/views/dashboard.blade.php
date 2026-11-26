@extends('layouts.master')

<<<<<<< Updated upstream
@php
    $clinicRole = session('clinic_role', 'Admin');
    $appointments = [
        ['time' => '9:00 AM', 'patient' => 'Sophia Carter', 'initials' => 'SC', 'tone' => 'purple', 'doctor' => 'Dr. Elena Morris', 'type' => 'General Checkup', 'status' => 'Confirmed'],
        ['time' => '10:30 AM', 'patient' => 'Liam Wilson', 'initials' => 'LW', 'tone' => 'blue', 'doctor' => 'Dr. James Chen', 'type' => 'Follow-up', 'status' => 'In Progress'],
        ['time' => '11:45 AM', 'patient' => 'Olivia Martinez', 'initials' => 'OM', 'tone' => 'amber', 'doctor' => 'Dr. Elena Morris', 'type' => 'Consultation', 'status' => 'Pending'],
        ['time' => '1:30 PM', 'patient' => 'Noah Thompson', 'initials' => 'NT', 'tone' => 'teal', 'doctor' => 'Dr. Priya Shah', 'type' => 'Dermatology', 'status' => 'Completed'],
    ];
@endphp

@section('title', 'Appointment Dashboard — ClinicCare')
@section('page-title', $clinicRole === 'Admin' ? 'Appointment Dashboard' : 'Today’s Overview')
@section('page-subtitle', now()->format('l, F j, Y'))

@section('content')
@if ($clinicRole === 'Admin')
    @php
        $stats = [
            ['calendar', "Today's appointments", '24', '+12% from yesterday', 'teal'],
            ['clock', 'Pending confirmation', '08', 'Needs your attention', 'amber'],
            ['check', 'Completed today', '13', '54% completion rate', 'green'],
            ['close', 'Cancelled / No-show', '03', '2 fewer than yesterday', 'red'],
        ];
        $bars = [['Mon', 18], ['Tue', 25], ['Wed', 21], ['Thu', 29], ['Fri', 24], ['Sat', 12], ['Sun', 5]];
    @endphp

    <div class="page-header">
        <div>
            <h2>Today’s appointment center</h2>
            <p>Monitor bookings, resolve pending requests, and keep the clinic on schedule.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('clinic.book') }}" class="btn primary">
                <x-clinic-icon name="plus" :size="17" />
                <span>Book Appointment</span>
            </a>
        </div>
    </div>

    <div class="stats-grid">
        @foreach ($stats as [$icon, $label, $value, $note, $tone])
            <section class="card stat-card">
                <div class="stat-icon {{ $tone }}"><x-clinic-icon :name="$icon" /></div>
                <div class="stat-label">{{ $label }}</div>
                <strong>{{ $value }}</strong>
                <small>{{ $note }}</small>
            </section>
        @endforeach
    </div>

    <div class="dashboard-grid">
        <section class="card chart-card">
            <div class="card-head">
                <div>
                    <h3>Appointments overview</h3>
                    <p>Appointments per day this week</p>
                </div>
                <select aria-label="Chart date range">
                    <option>This week</option>
                </select>
            </div>
            <div class="chart">
                <div class="y-labels"><span>30</span><span>20</span><span>10</span><span>0</span></div>
                <div class="bars">
                    @foreach ($bars as [$day, $val])
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $val }}</div>
                            <div class="bar" style="height: {{ $val * 4.8 }}px;"></div>
                            <span>{{ $day }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="chart-foot">
                <span><i class="legend-teal"></i>Appointments</span>
                <strong>134 total this week</strong>
            </div>
        </section>

        <section class="card upcoming-card">
            <div class="card-head">
                <div>
                    <h3>Upcoming appointments</h3>
                    <p>Next in line today</p>
                </div>
                <a href="{{ route('clinic.appointments') }}" class="text-btn">
                    View all <x-clinic-icon name="arrow" :size="15" />
                </a>
            </div>
            <div class="appointment-stack">
                @foreach ($appointments as $a)
                    @php $badgeClass = strtolower(str_replace(' ', '-', $a['status'])); @endphp
                    <a href="{{ route('clinic.appointments.show', 2054) }}" class="mini-appointment">
                        <span class="time">{{ $a['time'] }}</span>
                        <span class="avatar {{ $a['tone'] }} md" aria-label="{{ $a['patient'] }}">{{ $a['initials'] }}</span>
                        <span class="who">
                            <strong>{{ $a['patient'] }}</strong>
                            <small>{{ $a['type'] }} · {{ str_replace('Dr. ', '', $a['doctor']) }}</small>
                        </span>
                        <span class="badge {{ $badgeClass }}"><i></i>{{ $a['status'] }}</span>
                        <x-clinic-icon name="chevron" :size="16" />
                    </a>
                @endforeach
            </div>
        </section>
    </div>

    <section class="card quick-card">
        <div>
            <span class="eyebrow">APPOINTMENT OPERATIONS</span>
            <h3>Keep every clinic visit moving</h3>
        </div>
        <div>
            <a href="{{ route('clinic.patients') }}" class="btn secondary"><x-clinic-icon name="users" :size="17" /><span>Register Patient</span></a>
            <a href="{{ route('clinic.schedules') }}" class="btn secondary"><x-clinic-icon name="calendar" :size="17" /><span>Check Availability</span></a>
            <a href="{{ route('clinic.reports') }}" class="btn secondary"><x-clinic-icon name="report" :size="17" /><span>Appointment Reports</span></a>
        </div>
    </section>
@else
    <div class="page-header">
        <div>
            <h2>Good morning, {{ auth()->user()->name ?? 'Dr. Morris' }}</h2>
            <p>You have 6 appointments and 2 pending notes today.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('clinic.schedules') }}" class="btn secondary">
                <x-clinic-icon name="calendar" :size="17" />
                <span>View full schedule</span>
            </a>
        </div>
    </div>

    <div class="doctor-hero-grid">
        <section class="card next-patient">
            <div class="card-head">
                <div>
                    <span class="eyebrow">UP NEXT · 9:00 AM</span>
                    <h3>Your next patient</h3>
                </div>
                <span class="badge confirmed"><i></i>Confirmed</span>
            </div>
            <div class="patient-focus">
                <span class="avatar purple lg" aria-label="Sophia Carter">SC</span>
                <div>
                    <h2>Sophia Carter</h2>
                    <p>34 years · Female · Patient #PC-1042</p>
                </div>
            </div>
            <div class="reason">
                <span>Reason for visit</span>
                <strong>Annual wellness checkup</strong>
                <small>Last visit: February 12, 2025</small>
            </div>
            <div class="button-row">
                <a href="{{ route('clinic.appointments.show', 2054) }}" class="btn primary"><span>Open appointment</span></a>
                <a href="{{ route('clinic.patients') }}" class="btn secondary"><span>View profile</span></a>
            </div>
        </section>

        <div class="mini-stats">
            <section class="card">
                <div class="stat-icon teal"><x-clinic-icon name="calendar" /></div>
                <span>Appointments today</span>
                <strong>6</strong>
            </section>
            <section class="card">
                <div class="stat-icon amber"><x-clinic-icon name="edit" /></div>
                <span>Pending notes</span>
                <strong>2</strong>
            </section>
            <section class="card">
                <div class="stat-icon green"><x-clinic-icon name="check" /></div>
                <span>Completed</span>
                <strong>3</strong>
            </section>
        </div>
    </div>

    <section class="card schedule-card">
        <div class="card-head">
            <div>
                <h3>Today’s schedule</h3>
                <p>{{ now()->format('l, F j') }}</p>
            </div>
            <span class="live-dot"><i></i>Clinic open</span>
        </div>
        <div class="timeline">
            @foreach ($appointments as $i => $a)
                @php $badgeClass = strtolower(str_replace(' ', '-', $a['status'])); @endphp
                <a href="{{ route('clinic.appointments.show', 2054) }}" class="timeline-row {{ $i === 0 ? 'current' : '' }}">
                    <div class="timeline-time">
                        <strong>{{ $a['time'] }}</strong>
                        <small>{{ $i === 0 ? 'In 25 min' : '45 min' }}</small>
                    </div>
                    <i class="timeline-node"></i>
                    <span class="avatar {{ $a['tone'] }} md" aria-label="{{ $a['patient'] }}">{{ $a['initials'] }}</span>
                    <div class="timeline-copy">
                        <strong>{{ $a['patient'] }}</strong>
                        <small>{{ $a['type'] }}</small>
                    </div>
                    <span class="badge {{ $badgeClass }}"><i></i>{{ $a['status'] }}</span>
                    <x-clinic-icon name="chevron" :size="16" />
                </a>
            @endforeach
        </div>
    </section>
@endif
@endsection
=======
@section('title', 'Dashboard')
@section('page-title', 'Clinic Appointment Dashboard')
@section('page-subtitle', 'Step 1.2 • Valex Layout Verification')

@section('content')
<div class="row row-sm">
    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card custom-card">
            <div class="card-body">
                <p class="mb-1 tx-12 text-uppercase text-muted">Logged-In User</p>
                <h5 class="mb-1">{{ auth()->user()->name }}</h5>
                <span class="text-muted small">{{ auth()->user()->email }}</span>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card custom-card">
            <div class="card-body">
                <p class="mb-1 tx-12 text-uppercase text-muted">Project Group</p>
                <h5 class="mb-1">Group 03 — Clinic Appointment</h5>
                <span class="text-muted small">Laravel + Blade + Valex</span>
            </div>
        </div>
    </div>
</div>
@endsection
>>>>>>> Stashed changes
