@extends('layouts.admin')

@section('title', 'Appointment Dashboard')
@section('topbar-title', 'Appointment Dashboard')
@section('topbar-subtitle', now()->format('l, F j, Y'))

@section('content')
    <div class="page-header">
        <div>
            <h2>Today’s appointment center</h2>
            <p>Monitor bookings, resolve pending requests, and keep the clinic on schedule.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.appointments.create') }}" class="btn primary">
                <x-clinic-icon name="plus" size="17" /><span>Book Appointment</span>
            </a>
        </div>
    </div>

    <div class="stats-grid">
        <section class="card stat-card">
            <div class="stat-icon teal"><x-clinic-icon name="calendar" /></div>
            <div class="stat-label">Today's appointments</div>
            <strong>{{ str_pad((string) $todayCount, 2, '0', STR_PAD_LEFT) }}</strong>
            <small>{{ $yesterdayDiff >= 0 ? '+'.$yesterdayDiff : $yesterdayDiff }} from yesterday</small>
        </section>

        <section class="card stat-card">
            <div class="stat-icon amber"><x-clinic-icon name="clock" /></div>
            <div class="stat-label">Pending confirmation</div>
            <strong>{{ str_pad((string) $pendingCount, 2, '0', STR_PAD_LEFT) }}</strong>
            <small>{{ $pendingCount > 0 ? 'Needs your attention' : 'All requests reviewed' }}</small>
        </section>

        <section class="card stat-card">
            <div class="stat-icon green"><x-clinic-icon name="check" /></div>
            <div class="stat-label">Completed today</div>
            <strong>{{ str_pad((string) $completedToday, 2, '0', STR_PAD_LEFT) }}</strong>
            <small>{{ $completionRate }}% completion rate</small>
        </section>

        <section class="card stat-card">
            <div class="stat-icon red"><x-clinic-icon name="close" /></div>
            <div class="stat-label">Cancelled / No-show</div>
            <strong>{{ str_pad((string) $cancelledOrNoShowToday, 2, '0', STR_PAD_LEFT) }}</strong>
            <small>Today's exceptions</small>
        </section>
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
                <div class="y-labels">
                    <span>{{ $maxChartCount }}</span>
                    <span>{{ (int) round($maxChartCount * 0.66) }}</span>
                    <span>{{ (int) round($maxChartCount * 0.33) }}</span>
                    <span>0</span>
                </div>
                <div class="bars">
                    @foreach ($dailyChart as $bar)
                        @php
                            $barHeight = $bar['count'] > 0 ? max(14, (int) round(($bar['count'] / $maxChartCount) * 145)) : 4;
                        @endphp
                        <div class="bar-wrap">
                            <div class="bar-value">{{ $bar['count'] }}</div>
                            <div class="bar" style="height: {{ $barHeight }}px;"></div>
                            <span>{{ $bar['label'] }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="chart-foot">
                <span><i class="legend-teal"></i>Appointments</span>
                <strong>{{ $weeklyTotal }} total this week</strong>
            </div>
        </section>

        <section class="card upcoming-card">
            <div class="card-head">
                <div>
                    <h3>Upcoming appointments</h3>
                    <p>Next in line</p>
                </div>
                <a href="{{ route('admin.appointments.index') }}" class="text-btn">
                    View all <x-clinic-icon name="arrow" size="15" />
                </a>
            </div>
            <div class="appointment-stack">
                @forelse ($upcomingAppointments as $appt)
                    <a class="mini-appointment" href="{{ route('admin.appointments.show', $appt) }}">
                        <span class="time">{{ $appt->startTimeFormatted() }}</span>
                        <x-clinic-avatar :name="$appt->patient->full_name" :tone="$appt->patient->avatar_tone" />
                        <span class="who">
                            <strong>{{ $appt->patient->full_name }}</strong>
                            <small>{{ $appt->visit_type }} · {{ str_replace('Dr. ', '', $appt->doctor->full_name) }}</small>
                        </span>
                        <x-clinic-badge :status="$appt->status" />
                        <x-clinic-icon name="chevron" size="16" />
                    </a>
                @empty
                    <div class="empty-state" style="margin:12px;">
                        <x-clinic-icon name="calendar" />
                        <span>
                            <strong>No upcoming appointments</strong>
                            <small>Book a new visit to populate the queue.</small>
                        </span>
                    </div>
                @endforelse
            </div>
        </section>
    </div>

    <section class="card quick-card">
        <div>
            <span class="eyebrow">APPOINTMENT OPERATIONS</span>
            <h3>Keep every clinic visit moving</h3>
        </div>
        <div>
            <a href="{{ route('admin.patients.index') }}" class="btn secondary">
                <x-clinic-icon name="users" size="17" /><span>Register Patient</span>
            </a>
            <a href="{{ route('admin.schedules.index') }}" class="btn secondary">
                <x-clinic-icon name="calendar" size="17" /><span>Check Availability</span>
            </a>
            <a href="{{ route('admin.reports.index') }}" class="btn secondary">
                <x-clinic-icon name="report" size="17" /><span>Appointment Reports</span>
            </a>
        </div>
    </section>
@endsection
