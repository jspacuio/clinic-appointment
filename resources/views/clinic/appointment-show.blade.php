@extends('layouts.master')

@php
    $clinicRole = session('clinic_role', 'Admin');
    $patient = $appointment->patient;
@endphp

@section('title', 'Appointment ' . $appointment->appointment_code . ' — ClinicCare')
@section('page-title', 'Appointment Details')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ cancelModal: false }">
    <a href="{{ route('clinic.appointments') }}" class="back-link">
        <x-clinic-icon name="back" :size="17" />Back to appointments
    </a>

    @if (session('status'))
        <div class="info-banner" style="margin-bottom: 16px;">
            <x-clinic-icon name="check" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="detail-title">
        <div>
            <div>
                <h2>Appointment #{{ $appointment->appointment_code }}</h2>
                <span class="badge {{ $appointment->badge_class }}">
                    <i></i>{{ $appointment->status }}
                </span>
            </div>
            <p>{{ $appointment->appointment_date?->format('l, F j, Y') }} · {{ $appointment->appointment_time }}</p>
        </div>

        <div class="header-actions">
            @if ($appointment->status === 'Confirmed' || $appointment->status === 'Pending')
                <form method="POST" action="{{ route('clinic.appointments.update', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="In Progress">
                    <button type="submit" class="btn primary">
                        <span>Start consultation</span>
                    </button>
                </form>
            @endif

            @if ($appointment->status === 'In Progress')
                <form method="POST" action="{{ route('clinic.appointments.update', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="Completed">
                    <button type="submit" class="btn primary">
                        <span>Mark complete</span>
                    </button>
                </form>
            @endif

            <a href="{{ route('clinic.schedules') }}" class="btn secondary">
                <x-clinic-icon name="calendar" :size="17" /><span>Reschedule</span>
            </a>

            @if (!in_array($appointment->status, ['Cancelled', 'Completed']))
                <button type="button" class="btn danger" @click="cancelModal = true">
                    <span>Cancel</span>
                </button>
            @endif
        </div>
    </div>

    <div class="details-grid">
        <div class="details-main">
            <section class="card people-details">
                <div>
                    <span class="eyebrow">PATIENT</span>
                    @if ($patient)
                        <div class="patient-focus">
                            <span class="avatar {{ $patient->avatar_tone }} lg">{{ $patient->initials }}</span>
                            <div>
                                <h3>{{ $patient->full_name }}</h3>
                                <p>
                                    {{ $patient->age !== null ? $patient->age . ' years · ' : '' }}
                                    {{ $patient->sex ? $patient->sex . ' · ' : '' }}
                                    {{ $patient->patient_code }}
                                </p>
                                <span>{{ $patient->phone ?: ($patient->email ?: 'No contact') }}</span>
                            </div>
                        </div>
                        <a href="{{ route('clinic.patients.show', $patient) }}" class="text-btn">
                            View patient profile <x-clinic-icon name="arrow" :size="15" />
                        </a>
                    @endif
                </div>
                <div>
                    <span class="eyebrow">DOCTOR</span>
                    <div class="patient-focus">
                        <span class="avatar teal lg">DR</span>
                        <div>
                            <h3>{{ $appointment->doctor_name }}</h3>
                            <p>{{ $appointment->specialty }}</p>
                            <span>ClinicCare Practitioner</span>
                        </div>
                    </div>
                    <a href="{{ route('clinic.schedules') }}" class="text-btn">
                        View schedule <x-clinic-icon name="arrow" :size="15" />
                    </a>
                </div>
            </section>

            <section class="card">
                <h3>Appointment information</h3>
                <dl class="details-list two-col">
                    <div><dt>Reason for visit</dt><dd>{{ $appointment->reason ?: 'Not specified' }}</dd></div>
                    <div><dt>Duration</dt><dd>30 minutes</dd></div>
                    <div><dt>Booked by</dt><dd>{{ $appointment->booked_by ?: 'Admin' }}</dd></div>
                    <div><dt>Created</dt><dd>{{ $appointment->created_at?->format('F j, Y · g:i A') }}</dd></div>
                </dl>
            </section>

            @if ($clinicRole === 'Doctor')
                <form method="POST" action="{{ route('clinic.appointments.update', $appointment) }}" class="card notes-card">
                    @csrf
                    @method('PATCH')
                    <div class="card-head">
                        <div>
                            <h3>Consultation notes</h3>
                            <p>Visible to clinical staff only</p>
                        </div>
                        <span class="private-label"><x-clinic-icon name="shield" :size="14" />Private</span>
                    </div>
                    <label class="field">
                        <span>Clinical notes</span>
                        <textarea name="consultation_notes" placeholder="Add symptoms, observations, diagnosis and treatment plan...">{{ old('consultation_notes', $appointment->consultation_notes) }}</textarea>
                    </label>
                    <div class="button-row">
                        <button type="submit" class="btn primary">Save notes</button>
                    </div>
                </form>
            @endif
        </div>

        <section class="card status-card">
            <h3>Status timeline</h3>
            <div class="status-timeline">
                <div class="done">
                    <i><x-clinic-icon name="check" :size="14" /></i>
                    <div>
                        <strong>Appointment created</strong>
                        <span>{{ $appointment->created_at?->format('M j · g:i A') }}</span>
                        <small>by {{ $appointment->booked_by ?: 'Admin' }}</small>
                    </div>
                </div>
                <div class="{{ $appointment->status !== 'Pending' ? 'done' : 'next' }}">
                    <i>@if ($appointment->status !== 'Pending')<x-clinic-icon name="check" :size="14" />@else 2 @endif</i>
                    <div>
                        <strong>Confirmed</strong>
                        <span>{{ $appointment->status !== 'Pending' ? 'Booking confirmed' : 'Awaiting confirmation' }}</span>
                    </div>
                </div>
                <div class="{{ in_array($appointment->status, ['In Progress', 'Completed']) ? 'done' : 'next' }}">
                    <i>@if (in_array($appointment->status, ['In Progress', 'Completed']))<x-clinic-icon name="check" :size="14" />@else 3 @endif</i>
                    <div>
                        <strong>In progress</strong>
                        <span>{{ in_array($appointment->status, ['In Progress', 'Completed']) ? 'Consultation started' : 'Waiting to begin' }}</span>
                    </div>
                </div>
                <div class="{{ $appointment->status === 'Completed' ? 'done' : 'next' }}">
                    <i>@if ($appointment->status === 'Completed')<x-clinic-icon name="check" :size="14" />@else 4 @endif</i>
                    <div>
                        <strong>Completed</strong>
                        <span>{{ $appointment->status === 'Completed' ? 'Visit completed' : '—' }}</span>
                    </div>
                </div>
            </div>
            <div class="valid-states">
                <span>Current status</span>
                <strong>{{ $appointment->status }}</strong>
            </div>
        </section>
    </div>

    <div class="modal-layer" x-show="cancelModal" x-cloak @click.self="cancelModal = false">
        <form method="POST" action="{{ route('clinic.appointments.update', $appointment) }}" class="modal confirm-modal">
            @csrf
            @method('PATCH')
            <input type="hidden" name="status" value="Cancelled">
            <div class="danger-icon"><x-clinic-icon name="alert" /></div>
            <h2>Cancel appointment?</h2>
            <p>This will mark the appointment for {{ $patient?->full_name ?? 'this patient' }} as cancelled.</p>
            <label class="field">
                <span>Cancellation reason <b>*</b></span>
                <select name="cancellation_reason" required>
                    <option value="">Select a reason</option>
                    <option value="Patient requested">Patient requested</option>
                    <option value="Doctor unavailable">Doctor unavailable</option>
                    <option value="Scheduling conflict">Scheduling conflict</option>
                </select>
            </label>
            <label class="field">
                <span>Additional notes</span>
                <textarea name="cancellation_notes" placeholder="Add optional context..."></textarea>
            </label>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="cancelModal = false">Keep appointment</button>
                <button type="submit" class="btn danger">Cancel appointment</button>
            </div>
        </form>
    </div>
</div>
@endsection
