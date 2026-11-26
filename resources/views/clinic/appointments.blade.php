@extends('layouts.master')

@php
    $statuses = ['All', 'Pending', 'Confirmed', 'In Progress', 'Completed', 'Cancelled', 'No-show'];
@endphp

@section('title', 'Appointments — ClinicCare')
@section('page-title', 'Appointments')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div>
    <div class="page-header">
        <div>
            <h2>Appointments</h2>
            <p>Manage clinic visits and appointment status.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('clinic.book') }}" class="btn primary">
                <x-clinic-icon name="plus" :size="17" />
                <span>Book Appointment</span>
            </a>
        </div>
    </div>

    @if (session('status'))
        <div class="info-banner" style="margin-bottom: 16px;">
            <x-clinic-icon name="check" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="tabs">
        @foreach ($statuses as $label)
            @php
                $count = $label === 'All' ? $totalCount : ($statusCounts[$label] ?? 0);
                $query = array_filter([
                    'status' => $label === 'All' ? null : $label,
                    'search' => $filters['search'] ?: null,
                    'from_date' => $filters['from_date'] ?: null,
                    'to_date' => $filters['to_date'] ?: null,
                ]);
            @endphp
            <a href="{{ route('clinic.appointments', $query) }}" class="{{ $activeStatus === $label ? 'active' : '' }}">
                {{ $label }} <b>{{ $count }}</b>
            </a>
        @endforeach
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('clinic.appointments') }}" class="table-tools">
            @if ($activeStatus !== 'All')
                <input type="hidden" name="status" value="{{ $activeStatus }}">
            @endif
            <div class="search">
                <x-clinic-icon name="search" :size="18" />
                <input type="text"
                       name="search"
                       value="{{ $filters['search'] ?? '' }}"
                       aria-label="Search patient, doctor or ID..."
                       placeholder="Search patient, doctor or ID...">
            </div>
            <div class="filters">
                <input type="date" name="from_date" value="{{ $filters['from_date'] ?? '' }}" aria-label="From date">
                <span>to</span>
                <input type="date" name="to_date" value="{{ $filters['to_date'] ?? '' }}" aria-label="To date">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" :size="17" />
                    <span>Filter</span>
                </button>
                @if (!empty($filters['search']) || !empty($filters['from_date']) || !empty($filters['to_date']) || $activeStatus !== 'All')
                    <a href="{{ route('clinic.appointments') }}" class="btn ghost">Reset</a>
                @endif
            </div>
        </form>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Patient</th>
                        <th>Doctor</th>
                        <th>Visit type</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($appointments as $appointment)
                        @php $patient = $appointment->patient; @endphp
                        <tr>
                            <td>
                                <strong>{{ $appointment->appointment_time }}</strong>
                                <small class="cell-sub">{{ $appointment->appointment_date?->format('M j, Y') }}</small>
                            </td>
                            <td>
                                @if ($patient)
                                    <a href="{{ route('clinic.patients.show', $patient) }}" class="person-cell">
                                        <span class="avatar {{ $patient->avatar_tone }} md">{{ $patient->initials }}</span>
                                        <span><strong>{{ $patient->full_name }}</strong><small>{{ $patient->patient_code }}</small></span>
                                    </a>
                                @else
                                    <span>—</span>
                                @endif
                            </td>
                            <td>
                                {{ $appointment->doctor_name }}
                                <small class="cell-sub">{{ $appointment->specialty }}</small>
                            </td>
                            <td>{{ $appointment->visit_type }}</td>
                            <td>
                                <span class="badge {{ $appointment->badge_class }}"><i></i>{{ $appointment->status }}</span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('clinic.appointments.show', $appointment) }}"
                                       class="icon-btn"
                                       aria-label="View {{ $appointment->appointment_code }}">
                                        <x-clinic-icon name="chevron" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 36px 16px; color: var(--muted);">
                                No appointments found. Click <strong>Book Appointment</strong> to schedule one.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span>
                @if ($appointments->total() > 0)
                    Showing {{ $appointments->firstItem() }}–{{ $appointments->lastItem() }} of {{ $appointments->total() }} {{ Str::plural('appointment', $appointments->total()) }}
                @else
                    Showing 0 appointments
                @endif
            </span>
            @if ($appointments->hasPages())
                <div>
                    @if ($appointments->onFirstPage())
                        <button type="button" disabled aria-label="Previous page">‹</button>
                    @else
                        <a href="{{ $appointments->previousPageUrl() }}" aria-label="Previous page">‹</a>
                    @endif

                    @foreach ($appointments->getUrlRange(1, $appointments->lastPage()) as $page => $url)
                        @if ($page == $appointments->currentPage())
                            <button type="button" class="active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($appointments->hasMorePages())
                        <a href="{{ $appointments->nextPageUrl() }}" aria-label="Next page">›</a>
                    @else
                        <button type="button" disabled aria-label="Next page">›</button>
                    @endif
                </div>
            @endif
        </div>
    </section>
</div>
@endsection
