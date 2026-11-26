@extends('layouts.patient')

@section('title', 'My Appointments')
@section('topbar-title', 'My Appointments')
@section('topbar-subtitle', 'Patient ID: '.$patient->patient_code)

@section('content')
    <div class="page-header">
        <div>
            <h2>My Appointments</h2>
            <p>View your upcoming bookings, past visits, and request status.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('patient.appointments.create') }}" class="btn primary">
                <x-clinic-icon name="plus" size="17" /><span>Book Appointment</span>
            </a>
        </div>
    </div>

    <div class="tabs">
        <a href="{{ route('patient.appointments.index', ['status' => 'all', 'search' => $search]) }}" class="{{ $activeStatus === 'all' ? 'active' : '' }}">
            All <b>{{ $totalCount }}</b>
        </a>
        @foreach ($statuses as $st)
            <a href="{{ route('patient.appointments.index', ['status' => $st->value, 'search' => $search]) }}" class="{{ $activeStatus === $st->value ? 'active' : '' }}">
                {{ $st->label() }} <b>{{ $statusCounts[$st->value] ?? 0 }}</b>
            </a>
        @endforeach
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('patient.appointments.index') }}" class="table-tools">
            <input type="hidden" name="status" value="{{ $activeStatus }}">
            <div class="search">
                <x-clinic-icon name="search" size="18" />
                <input type="search" name="search" value="{{ $search }}" placeholder="Search by code, doctor, or category...">
            </div>
            <div class="filters">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Search</span>
                </button>
            </div>
        </form>

        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>Code</th>
                    <th>Doctor &amp; Category</th>
                    <th>Date &amp; Time</th>
                    <th>Visit Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($appointments as $appt)
                    <tr>
                        <td><code>#{{ $appt->appointment_code }}</code></td>
                        <td>
                            <div class="person-cell">
                                <x-clinic-avatar :name="$appt->doctor->full_name" :tone="$appt->doctor->avatar_tone" size="sm" />
                                <span>
                                    <strong>{{ $appt->doctor->full_name }}</strong>
                                    <small>{{ $appt->doctor->specialization }}</small>
                                </span>
                            </div>
                        </td>
                        <td>
                            <strong>{{ $appt->appointment_date->format('M j, Y') }}</strong>
                            <span class="cell-sub">{{ $appt->startTimeFormatted() }} ({{ $appt->duration_minutes }} min)</span>
                        </td>
                        <td>{{ $appt->visit_type }}</td>
                        <td><x-clinic-badge :status="$appt->status" /></td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('patient.appointments.show', $appt) }}" title="View details">
                                    <x-clinic-icon name="arrow" size="16" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:32px; color:var(--muted);">
                            No appointments found. <a href="{{ route('patient.appointments.create') }}" class="text-btn">Book your first appointment &rarr;</a>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $appointments->links() }}
    </section>
@endsection
