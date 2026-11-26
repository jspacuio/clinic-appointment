@extends('layouts.doctor')

@section('title', 'My Appointments')
@section('topbar-title', 'My Appointments')
@section('topbar-subtitle', $doctor->full_name.' · '.$doctor->specialization)

@section('content')
    <div class="page-header">
        <div>
            <h2>My Appointments</h2>
            <p>Review your assigned patient visits, start consultations, and record clinical notes.</p>
        </div>
    </div>

    <div class="tabs">
        <a href="{{ route('doctor.appointments.index') }}" class="{{ $activeStatus === 'all' ? 'active' : '' }}">
            All <b>{{ $totalCount }}</b>
        </a>
        @foreach ($statuses as $status)
            <a href="{{ route('doctor.appointments.index', ['status' => $status->value]) }}" class="{{ $activeStatus === $status->value ? 'active' : '' }}">
                {{ $status->label() }} <b>{{ $statusCounts[$status->value] ?? 0 }}</b>
            </a>
        @endforeach
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('doctor.appointments.index') }}" class="table-tools">
            @if ($activeStatus !== 'all')
                <input type="hidden" name="status" value="{{ $activeStatus }}">
            @endif
            <div class="search">
                <x-clinic-icon name="search" size="18" />
                <input type="search" name="search" value="{{ $search }}" placeholder="Search patient name or ID...">
            </div>
            <div class="filters">
                <input type="date" name="from" value="{{ $fromDate }}" aria-label="From date">
                <span>to</span>
                <input type="date" name="to" value="{{ $toDate }}" aria-label="To date">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Filter</span>
                </button>
            </div>
        </form>

        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>Time</th>
                    <th>Patient</th>
                    <th>Visit type</th>
                    <th>Clinical notes</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($appointments as $appt)
                    <tr>
                        <td>
                            <strong>{{ $appt->startTimeFormatted() }}</strong>
                            <small class="cell-sub">{{ $appt->appointment_date->format('M j, Y') }}</small>
                        </td>
                        <td>
                            <div class="person-cell">
                                <x-clinic-avatar :name="$appt->patient->full_name" :tone="$appt->patient->avatar_tone" />
                                <span>
                                    <strong>{{ $appt->patient->full_name }}</strong>
                                    <small>{{ $appt->patient->patient_code }} · {{ $appt->appointment_code }}</small>
                                </span>
                            </div>
                        </td>
                        <td>{{ $appt->visit_type }}</td>
                        <td>
                            @if (! empty($appt->clinical_notes))
                                <span class="simple-status active">{{ $appt->notes_draft ? 'Draft saved' : 'Recorded' }}</span>
                            @else
                                <span class="simple-status inactive">Pending</span>
                            @endif
                        </td>
                        <td><x-clinic-badge :status="$appt->status" /></td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('doctor.appointments.show', $appt) }}" aria-label="Open appointment">
                                    <x-clinic-icon name="chevron" size="17" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:32px; color:var(--muted);">
                            No appointments found for your filter.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $appointments->links() }}
    </section>
@endsection
