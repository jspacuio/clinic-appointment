@extends('layouts.doctor')

@section('title', 'Appointment #'.$appointment->appointment_code)
@section('topbar-title', 'Appointment Details')
@section('topbar-subtitle', $doctor->full_name.' · '.$doctor->specialization)

@section('content')
    <a href="{{ route('doctor.appointments.index') }}" class="back-link">
        <x-clinic-icon name="back" size="17" />Back to my appointments
    </a>

    <div class="detail-title">
        <div>
            <div>
                <h2>Appointment #{{ $appointment->appointment_code }}</h2>
                <x-clinic-badge :status="$appointment->status" />
            </div>
            <p>{{ $appointment->appointment_date->format('l, F j, Y') }} · {{ $appointment->startTimeFormatted() }}–{{ $appointment->endTimeFormatted() }}</p>
        </div>

        <div class="header-actions">
            @if ($appointment->status === \App\Enums\AppointmentStatus::Pending)
                <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="btn primary">Confirm appointment</button>
                </form>
            @elseif ($appointment->status === \App\Enums\AppointmentStatus::Confirmed)
                <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="in_progress">
                    <button type="submit" class="btn primary">Start consultation</button>
                </form>
            @elseif ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                @if ($appointment->doctor_completed_at === null)
                    <form method="POST" action="{{ route('doctor.appointments.status', $appointment) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn primary">Confirm completed</button>
                    </form>
                @else
                    <span class="badge confirmed">You confirmed completion · Waiting for patient</span>
                @endif
            @endif

            @if (! in_array($appointment->status, [\App\Enums\AppointmentStatus::Completed, \App\Enums\AppointmentStatus::Cancelled, \App\Enums\AppointmentStatus::NoShow], true))
                <button type="button" class="btn danger" onclick="openModal('doctor-cancel-modal')">Cancel</button>
            @endif
        </div>
    </div>

    <div class="details-grid">
        <div class="details-main">
            <section class="card people-details">
                <div>
                    <span class="eyebrow">PATIENT</span>
                    <div class="patient-focus">
                        <x-clinic-avatar :name="$appointment->patient->full_name" :tone="$appointment->patient->avatar_tone" size="lg" />
                        <div>
                            <h3>{{ $appointment->patient->full_name }}</h3>
                            <p>{{ $appointment->patient->age() ?? '—' }} years · {{ $appointment->patient->sex }} · {{ $appointment->patient->patient_code }}</p>
                            <span>{{ $appointment->patient->phone }}</span>
                        </div>
                    </div>
                    <a href="{{ route('doctor.patients.show', $appointment->patient) }}" class="text-btn">
                        View patient profile <x-clinic-icon name="arrow" size="15" />
                    </a>
                </div>
                <div>
                    <span class="eyebrow">DOCTOR</span>
                    <div class="patient-focus">
                        <x-clinic-avatar :name="$appointment->doctor->full_name" :tone="$appointment->doctor->avatar_tone" size="lg" />
                        <div>
                            <h3>{{ $appointment->doctor->full_name }}</h3>
                            <p>{{ $appointment->doctor->specialization }}</p>
                            <span>License {{ $appointment->doctor->license_number }}</span>
                        </div>
                    </div>
                    <a href="{{ route('doctor.schedules.index') }}" class="text-btn">
                        View my schedule <x-clinic-icon name="arrow" size="15" />
                    </a>
                </div>
            </section>

            <section class="card">
                <h3>Appointment information</h3>
                <dl class="details-list two-col">
                    <div>
                        <dt>Reason for visit</dt>
                        <dd>{{ $appointment->reason ?: $appointment->visit_type }}</dd>
                    </div>
                    <div>
                        <dt>Duration</dt>
                        <dd>{{ $appointment->duration_minutes }} minutes</dd>
                    </div>
                    <div>
                        <dt>Booked by</dt>
                        <dd>{{ $appointment->bookedBy?->name ?? 'System' }} ({{ $appointment->bookedBy?->role?->label() ?? 'Staff' }})</dd>
                    </div>
                    <div>
                        <dt>Created</dt>
                        <dd>{{ $appointment->created_at?->format('F j, Y · g:i A') }}</dd>
                    </div>
                </dl>
            </section>

            {{-- Private Clinical Notes (Doctor Portal Only) --}}
            <section class="card notes-card">
                <div class="card-head">
                    <div>
                        <h3>Consultation notes</h3>
                        <p>Visible to clinical staff only</p>
                    </div>
                    <span class="private-label">
                        <x-clinic-icon name="shield" size="14" />Private
                    </span>
                </div>

                <form method="POST" action="{{ route('doctor.appointments.notes', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <label class="field">
                        <span>Clinical notes</span>
                        <textarea name="clinical_notes" placeholder="Add symptoms, observations, diagnosis and treatment plan..." required>{{ old('clinical_notes', $appointment->clinical_notes) }}</textarea>
                    </label>
                    <div class="button-row">
                        <button type="submit" name="is_draft" value="0" class="btn primary">Save notes</button>
                        <button type="submit" name="is_draft" value="1" class="btn secondary">Save draft</button>
                    </div>
                </form>
            </section>
        </div>

        <section class="card status-card">
            <h3>Status timeline</h3>
            <div class="status-timeline">
                <div class="done">
                    <i><x-clinic-icon name="check" size="14" /></i>
                    <div>
                        <strong>Appointment created</strong>
                        <span>{{ $appointment->created_at?->format('M j · g:i A') }}</span>
                        <small>by {{ $appointment->bookedBy?->name ?? 'System' }}</small>
                    </div>
                </div>
                <div class="{{ $appointment->confirmed_at !== null ? 'done' : 'next' }}">
                    <i>@if ($appointment->confirmed_at !== null)<x-clinic-icon name="check" size="14" />@else 2 @endif</i>
                    <div>
                        <strong>Confirmed</strong>
                        <span>{{ $appointment->confirmed_at?->format('M j · g:i A') ?? 'Awaiting confirmation' }}</span>
                    </div>
                </div>
                <div class="{{ $appointment->started_at !== null ? 'done' : 'next' }}">
                    <i>@if ($appointment->started_at !== null)<x-clinic-icon name="check" size="14" />@else 3 @endif</i>
                    <div>
                        <strong>In progress</strong>
                        <span>{{ $appointment->started_at?->format('M j · g:i A') ?? 'Waiting to begin' }}</span>
                    </div>
                </div>
                <div class="{{ $appointment->completed_at !== null ? 'done' : 'next' }}">
                    <i>@if ($appointment->completed_at !== null)<x-clinic-icon name="check" size="14" />@else 4 @endif</i>
                    <div>
                        <strong>Completed (Doctor &amp; Patient)</strong>
                        <span>{{ $appointment->completed_at?->format('M j · g:i A') ?? 'Confirmed by both when finished' }}</span>
                        @if ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                            <small>Doctor: {{ $appointment->doctor_completed_at ? 'Confirmed' : 'Pending' }} · Patient: {{ $appointment->patient_completed_at ? 'Confirmed' : 'Pending' }}</small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="valid-states">
                <span>Valid next action</span>
                <strong>
                    @if ($appointment->status === \App\Enums\AppointmentStatus::Pending)
                        Confirm appointment
                    @elseif ($appointment->status === \App\Enums\AppointmentStatus::Confirmed)
                        Start consultation
                    @elseif ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                        {{ $appointment->doctor_completed_at === null ? 'Confirm completed (with patient)' : 'Waiting for patient to confirm completion' }}
                    @else
                        No further action
                    @endif
                </strong>
            </div>
        </section>
    </div>

    {{-- Doctor Cancel Modal --}}
    <div id="doctor-cancel-modal" class="modal-layer" hidden>
        <div class="modal confirm-modal">
            <div class="danger-icon"><x-clinic-icon name="alert" /></div>
            <h2>Cancel appointment?</h2>
            <p>This will notify {{ $appointment->patient->full_name }} and release the time slot.</p>
            <form method="POST" action="{{ route('doctor.appointments.cancel', $appointment) }}">
                @csrf
                @method('PATCH')
                <label class="field">
                    <span>Cancellation reason <b>*</b></span>
                    <select name="cancellation_reason" required>
                        <option value="">Select a reason</option>
                        <option value="Doctor unavailable">Doctor unavailable</option>
                        <option value="Patient requested">Patient requested</option>
                        <option value="Scheduling conflict">Scheduling conflict</option>
                    </select>
                </label>
                <label class="field">
                    <span>Additional notes</span>
                    <textarea name="cancellation_notes" placeholder="Add optional context..."></textarea>
                </label>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('doctor-cancel-modal')">Keep appointment</button>
                    <button type="submit" class="btn danger">Cancel appointment</button>
                </div>
            </form>
        </div>
    </div>
@endsection
