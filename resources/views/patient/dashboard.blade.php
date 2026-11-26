@extends('layouts.patient')

@section('title', 'Patient Overview')
@section('topbar-title', 'Patient Overview')
@section('topbar-subtitle', 'Welcome back, '.$patient->full_name)

@section('content')
    <div class="page-header">
        <div>
            <h2>Hello, {{ $patient->full_name }}</h2>
            <p>Browse a clinic category, choose your preferred doctor, and book an appointment in minutes.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('patient.appointments.create') }}" class="btn primary">
                <x-clinic-icon name="plus" size="17" /><span>Book Appointment</span>
            </a>
        </div>
    </div>

    <div class="stats-grid" style="grid-template-columns: repeat(3, 1fr);">
        <section class="card stat-card">
            <div class="stat-icon teal"><x-clinic-icon name="calendar" /></div>
            <div class="stat-label">Upcoming Visits</div>
            <strong>{{ $upcomingCount }}</strong>
            <small>Pending or confirmed appointments</small>
        </section>
        <section class="card stat-card">
            <div class="stat-icon green"><x-clinic-icon name="check" /></div>
            <div class="stat-label">Completed Visits</div>
            <strong>{{ $completedCount }}</strong>
            <small>Past consultations completed</small>
        </section>
        <section class="card stat-card">
            <div class="stat-icon amber"><x-clinic-icon name="clock" /></div>
            <div class="stat-label">Total Appointments</div>
            <strong>{{ $totalAppointments }}</strong>
            <small>Patient ID: {{ $patient->patient_code }}</small>
        </section>
    </div>

    <section class="card" style="margin-top:16px; padding:20px;">
        <div class="card-head">
            <div>
                <h3>Book by Category</h3>
                <p>Select a medical category to see available doctors and time slots.</p>
            </div>
            <a href="{{ route('patient.appointments.create') }}" class="text-btn">
                View all doctors <x-clinic-icon name="arrow" size="14" />
            </a>
        </div>

        <div class="category-grid">
            @forelse ($categories as $cat)
                <a href="{{ route('patient.appointments.create', ['category' => $cat->specialization]) }}" class="card category-card">
                    <div class="stat-icon teal"><x-clinic-icon name="doctor" /></div>
                    <strong>{{ $cat->specialization }}</strong>
                    <small>{{ $cat->doctors_count }} {{ Str::plural('doctor', $cat->doctors_count) }} available</small>
                    <span class="text-btn">Book now <x-clinic-icon name="arrow" size="13" /></span>
                </a>
            @empty
                <div style="grid-column:1/-1; padding:24px; text-align:center; color:var(--muted);">
                    No active doctors are available right now.
                </div>
            @endforelse
        </div>
    </section>

    <section class="card schedule-card">
        <div class="card-head">
            <div>
                <h3>My Upcoming Appointments</h3>
                <p>Track confirmation status and visit schedules.</p>
            </div>
            <a href="{{ route('patient.appointments.index') }}" class="text-btn">
                All appointments <x-clinic-icon name="arrow" size="14" />
            </a>
        </div>

        <div class="timeline">
            @forelse ($upcomingAppointments as $appt)
                <a href="{{ route('patient.appointments.show', $appt) }}" class="timeline-row">
                    <div class="timeline-time">
                        <strong>{{ $appt->appointment_date->format('M j') }}</strong>
                        <small>{{ $appt->startTimeFormatted() }}</small>
                    </div>
                    <i class="timeline-node"></i>
                    <x-clinic-avatar :name="$appt->doctor->full_name" :tone="$appt->doctor->avatar_tone" />
                    <div class="timeline-copy">
                        <strong>{{ $appt->doctor->full_name }}</strong>
                        <small>{{ $appt->doctor->specialization }} · {{ $appt->visit_type }}</small>
                    </div>
                    <x-clinic-badge :status="$appt->status" />
                    <x-clinic-icon name="chevron" size="16" />
                </a>
            @empty
                <div style="padding:28px; text-align:center; color:var(--muted); font-size:12px;">
                    You have no upcoming appointments. <a href="{{ route('patient.appointments.create') }}" class="text-btn">Book one now &rarr;</a>
                </div>
            @endforelse
        </div>
    </section>
@endsection
