@extends('layouts.doctor')

@section('title', 'Today’s Overview')
@section('topbar-title', 'Today’s Overview')
@section('topbar-subtitle', now()->format('l, F j, Y'))

@section('content')
    <div class="page-header">
        <div>
            <h2>Good morning, {{ $doctor->full_name }}</h2>
            <p>You have {{ $todayAppointments->count() }} {{ Str::plural('appointment', $todayAppointments->count()) }} and {{ $pendingNotesCount }} pending {{ Str::plural('note', $pendingNotesCount) }} today.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('doctor.schedules.index') }}" class="btn secondary">
                <x-clinic-icon name="calendar" size="17" /><span>View full schedule</span>
            </a>
        </div>
    </div>

    <div class="doctor-hero-grid">
        <section class="card next-patient">
            @if ($nextAppointment !== null)
                <div class="card-head">
                    <div>
                        <span class="eyebrow">UP NEXT · {{ $nextAppointment->startTimeFormatted() }}</span>
                        <h3>Your next patient</h3>
                    </div>
                    <x-clinic-badge :status="$nextAppointment->status" />
                </div>
                <div class="patient-focus">
                    <x-clinic-avatar :name="$nextAppointment->patient->full_name" :tone="$nextAppointment->patient->avatar_tone" size="lg" />
                    <div>
                        <h2>{{ $nextAppointment->patient->full_name }}</h2>
                        <p>{{ $nextAppointment->patient->age() ?? '—' }} years · {{ $nextAppointment->patient->sex }} · Patient #{{ $nextAppointment->patient->patient_code }}</p>
                    </div>
                </div>
                <div class="reason">
                    <span>Reason for visit</span>
                    <strong>{{ $nextAppointment->reason ?: $nextAppointment->visit_type }}</strong>
                    <small>
                        Last visit: {{ $lastVisitForNextPatient?->appointment_date?->format('F j, Y') ?? 'First recorded visit' }}
                    </small>
                </div>
                <div class="button-row">
                    <a href="{{ route('doctor.appointments.show', $nextAppointment) }}" class="btn primary">Open appointment</a>
                    <a href="{{ route('doctor.patients.show', $nextAppointment->patient) }}" class="btn secondary">View profile</a>
                </div>
            @else
                <div class="card-head">
                    <div>
                        <span class="eyebrow">TODAY'S QUEUE</span>
                        <h3>No remaining patients today</h3>
                    </div>
                </div>
                <div class="empty-state" style="margin-top:20px;">
                    <x-clinic-icon name="calendar" />
                    <span>
                        <strong>All caught up</strong>
                        <small>No pending or confirmed visits left on today's schedule.</small>
                    </span>
                </div>
            @endif
        </section>

        <div class="mini-stats">
            <section class="card">
                <div class="stat-icon teal"><x-clinic-icon name="calendar" /></div>
                <span>Appointments today</span>
                <strong>{{ $todayAppointments->count() }}</strong>
            </section>
            <section class="card">
                <div class="stat-icon amber"><x-clinic-icon name="edit" /></div>
                <span>Pending notes</span>
                <strong>{{ $pendingNotesCount }}</strong>
            </section>
            <section class="card">
                <div class="stat-icon green"><x-clinic-icon name="check" /></div>
                <span>Completed</span>
                <strong>{{ $completedTodayCount }}</strong>
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
            @forelse ($todayAppointments as $index => $appt)
                <a href="{{ route('doctor.appointments.show', $appt) }}" class="timeline-row {{ $nextAppointment?->id === $appt->id ? 'current' : '' }}">
                    <div class="timeline-time">
                        <strong>{{ $appt->startTimeFormatted() }}</strong>
                        <small>{{ $appt->duration_minutes }} min</small>
                    </div>
                    <i class="timeline-node"></i>
                    <x-clinic-avatar :name="$appt->patient->full_name" :tone="$appt->patient->avatar_tone" />
                    <div class="timeline-copy">
                        <strong>{{ $appt->patient->full_name }}</strong>
                        <small>{{ $appt->visit_type }}</small>
                    </div>
                    <x-clinic-badge :status="$appt->status" />
                    <x-clinic-icon name="chevron" size="16" />
                </a>
            @empty
                <div style="padding:28px; text-align:center; color:var(--muted); font-size:12px;">
                    No appointments scheduled for today.
                </div>
            @endforelse
        </div>
    </section>
@endsection
