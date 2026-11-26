@extends('layouts.patient')

@section('title', 'Appointment #'.$appointment->appointment_code)
@section('topbar-title', 'Appointment Details')
@section('topbar-subtitle', 'Patient ID: '.$patient->patient_code)

@section('content')
    <a href="{{ route('patient.appointments.index') }}" class="back-link">
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
            @if ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                @if ($appointment->patient_completed_at === null)
                    <form method="POST" action="{{ route('patient.appointments.status', $appointment) }}">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit" class="btn primary">Confirm completed</button>
                    </form>
                @else
                    <span class="badge confirmed">You confirmed completion · Waiting for doctor</span>
                @endif
            @endif

            @if (in_array($appointment->status, [\App\Enums\AppointmentStatus::Pending, \App\Enums\AppointmentStatus::Confirmed], true))
                <button type="button" class="btn danger" onclick="openModal('patient-cancel-modal')">Cancel appointment</button>
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
                            <p>{{ $appointment->patient->patient_code }} · {{ $appointment->patient->sex }}</p>
                            <span>{{ $appointment->patient->phone }}</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="eyebrow">DOCTOR &amp; CATEGORY</span>
                    <div class="patient-focus">
                        <x-clinic-avatar :name="$appointment->doctor->full_name" :tone="$appointment->doctor->avatar_tone" size="lg" />
                        <div>
                            <h3>{{ $appointment->doctor->full_name }}</h3>
                            <p>{{ $appointment->doctor->specialization }}</p>
                            <span>{{ $appointment->doctor->availability_summary ?: 'Mon–Fri' }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="card">
                <h3>Appointment information</h3>
                <dl class="details-list two-col">
                    <div>
                        <dt>Visit type / Category</dt>
                        <dd>{{ $appointment->visit_type }}</dd>
                    </div>
                    <div>
                        <dt>Duration</dt>
                        <dd>{{ $appointment->duration_minutes }} minutes</dd>
                    </div>
                    <div>
                        <dt>Reason for visit</dt>
                        <dd>{{ $appointment->reason ?: 'No additional notes provided' }}</dd>
                    </div>
                    <div>
                        <dt>Booked on</dt>
                        <dd>{{ $appointment->created_at?->format('F j, Y · g:i A') }}</dd>
                    </div>
                </dl>

                @if ($appointment->status === \App\Enums\AppointmentStatus::Cancelled)
                    <div class="error-banner" style="margin-top:16px;">
                        <x-clinic-icon name="alert" />
                        <span>
                            <strong>Cancelled: {{ $appointment->cancellation_reason }}</strong>
                            {{ $appointment->cancellation_notes }}
                        </span>
                    </div>
                @endif
            </section>
        </div>

        <section class="card status-card">
            <h3>Status timeline</h3>
            <div class="status-timeline">
                <div class="done">
                    <i><x-clinic-icon name="check" size="14" /></i>
                    <div>
                        <strong>Appointment requested</strong>
                        <span>{{ $appointment->created_at?->format('M j · g:i A') }}</span>
                    </div>
                </div>
                <div class="{{ $appointment->confirmed_at !== null ? 'done' : 'next' }}">
                    <i>@if ($appointment->confirmed_at !== null)<x-clinic-icon name="check" size="14" />@else 2 @endif</i>
                    <div>
                        <strong>Confirmed</strong>
                        <span>{{ $appointment->confirmed_at?->format('M j · g:i A') ?? 'Awaiting doctor confirmation' }}</span>
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
                            <small>Doctor: {{ $appointment->doctor_completed_at ? 'Confirmed' : 'Pending' }} · You: {{ $appointment->patient_completed_at ? 'Confirmed' : 'Pending' }}</small>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    @if (in_array($appointment->status, [\App\Enums\AppointmentStatus::Pending, \App\Enums\AppointmentStatus::Confirmed], true))
        <div id="patient-cancel-modal" class="modal-layer" hidden>
            <div class="modal confirm-modal">
                <div class="danger-icon"><x-clinic-icon name="alert" /></div>
                <h2>Cancel appointment?</h2>
                <p>This will cancel your booking with {{ $appointment->doctor->full_name }} and release the time slot.</p>
                <form method="POST" action="{{ route('patient.appointments.cancel', $appointment) }}">
                    @csrf
                    @method('PATCH')
                    <label class="field">
                        <span>Reason for cancelling <b>*</b></span>
                        <select name="cancellation_reason" required>
                            <option value="">Select a reason</option>
                            <option value="Patient requested">Schedule conflict</option>
                            <option value="Rescheduling">Need to reschedule</option>
                            <option value="Feeling better">No longer needed</option>
                        </select>
                    </label>
                    <label class="field">
                        <span>Additional notes</span>
                        <textarea name="cancellation_notes" placeholder="Optional details..."></textarea>
                    </label>
                    <div class="modal-foot">
                        <button type="button" class="btn secondary" onclick="closeModal('patient-cancel-modal')">Keep appointment</button>
                        <button type="submit" class="btn danger">Cancel appointment</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
