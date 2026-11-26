@extends('layouts.doctor')

@section('title', $patient->full_name)
@section('topbar-title', 'Patient Profile')
@section('topbar-subtitle', $doctor->full_name.' · '.$doctor->specialization)

@section('content')
    <a href="{{ route('doctor.patients.index') }}" class="back-link">
        <x-clinic-icon name="back" size="17" />Back to my patients
    </a>

    <section class="card profile-banner">
        <div class="patient-focus">
            <x-clinic-avatar :name="$patient->full_name" :tone="$patient->avatar_tone" size="lg" />
            <div>
                <div class="profile-title">
                    <h2>{{ $patient->full_name }}</h2>
                    <span class="simple-status {{ $patient->is_active ? 'active' : 'inactive' }}">
                        {{ $patient->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p>Patient #{{ $patient->patient_code }} · {{ $patient->age() ?? '—' }} years · {{ $patient->sex }}</p>
                <span>{{ $patient->phone }} @if ($patient->email)· {{ $patient->email }}@endif</span>
            </div>
        </div>
    </section>

    <div class="profile-grid">
        <section class="card">
            <h3>Patient information</h3>
            <dl class="details-list">
                <div>
                    <dt>Date of birth</dt>
                    <dd>{{ $patient->date_of_birth?->format('F j, Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt>Address</dt>
                    <dd>{{ $patient->address ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Emergency contact</dt>
                    <dd>{{ $patient->emergency_contact ?: '—' }}</dd>
                </div>
                <div>
                    <dt>Registered</dt>
                    <dd>{{ $patient->created_at?->format('F j, Y') }}</dd>
                </div>
            </dl>
        </section>

        <section class="card">
            <h3>Care summary (with you)</h3>
            <div class="summary-numbers">
                <div><strong>{{ $totalVisits }}</strong><span>Total visits</span></div>
                <div><strong>{{ $upcomingVisits }}</strong><span>Upcoming</span></div>
                <div><strong>{{ $noShowCount }}</strong><span>No-shows</span></div>
            </div>
            @if ($patient->allergy_note)
                <div class="notice">
                    <x-clinic-icon name="alert" />
                    <span>
                        <strong>{{ $patient->allergy_note }}</strong>
                        <small>Recorded {{ $patient->allergy_recorded_at?->format('M j, Y') ?? 'on file' }}</small>
                    </span>
                </div>
            @endif
        </section>
    </div>

    <section class="card visit-history">
        <div class="card-head">
            <div>
                <h3>Visit history</h3>
                <p>Consultations with {{ $doctor->full_name }}</p>
            </div>
        </div>

        @forelse ($visits as $visit)
            <div class="visit-row">
                <div class="date-box">
                    <strong>{{ $visit->appointment_date->format('d') }}</strong>
                    <span>{{ $visit->appointment_date->format('M') }}</span>
                </div>
                <div>
                    <strong>{{ $visit->reason ?: $visit->visit_type }}</strong>
                    <small>{{ $visit->startTimeFormatted() }} · {{ $visit->clinical_notes ?: 'No clinical note recorded' }}</small>
                </div>
                <x-clinic-badge :status="$visit->status" />
                <a href="{{ route('doctor.appointments.show', $visit) }}" class="icon-btn" aria-label="View appointment">
                    <x-clinic-icon name="chevron" />
                </a>
            </div>
        @empty
            <div style="padding:24px; text-align:center; color:var(--muted); font-size:12px;">No visits found.</div>
        @endforelse
    </section>
@endsection
