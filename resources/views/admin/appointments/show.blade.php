@extends('layouts.admin')

@section('title', 'Appointment #'.$appointment->appointment_code)
@section('topbar-title', 'Appointment Details')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <a href="{{ route('admin.appointments.index') }}" class="back-link">
        <x-clinic-icon name="back" size="17" />Back to appointments
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
            @if (! in_array($appointment->status, [\App\Enums\AppointmentStatus::Completed, \App\Enums\AppointmentStatus::Cancelled, \App\Enums\AppointmentStatus::NoShow], true))
                <button type="button" class="btn secondary" onclick="openModal('reschedule-modal')">
                    <x-clinic-icon name="calendar" size="16" /><span>Reschedule</span>
                </button>
                <button type="button" class="btn danger" onclick="openModal('cancel-modal')">Cancel</button>
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
                    <a href="{{ route('admin.patients.show', $appointment->patient) }}" class="text-btn">
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
                    <a href="{{ route('admin.schedules.index', ['doctor_id' => $appointment->doctor_id]) }}" class="text-btn">
                        View schedule <x-clinic-icon name="arrow" size="15" />
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
                        <strong>Appointment created</strong>
                        <span>{{ $appointment->created_at?->format('M j · g:i A') }}</span>
                        <small>by {{ $appointment->bookedBy?->name ?? 'System' }}</small>
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
                        <span>{{ $appointment->started_at?->format('M j · g:i A') ?? 'Started by doctor' }}</span>
                    </div>
                </div>
                <div class="{{ $appointment->completed_at !== null ? 'done' : 'next' }}">
                    <i>@if ($appointment->completed_at !== null)<x-clinic-icon name="check" size="14" />@else 4 @endif</i>
                    <div>
                        <strong>Completed (Doctor &amp; Patient)</strong>
                        <span>{{ $appointment->completed_at?->format('M j · g:i A') ?? 'Waiting for both to confirm completion' }}</span>
                        @if ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                            <small>
                                Doctor: {{ $appointment->doctor_completed_at ? 'Confirmed' : 'Pending' }} ·
                                Patient: {{ $appointment->patient_completed_at ? 'Confirmed' : 'Pending' }}
                            </small>
                        @endif
                    </div>
                </div>
            </div>

            <div class="valid-states">
                <span>Progression rule</span>
                <strong>
                    @if ($appointment->status === \App\Enums\AppointmentStatus::Pending)
                        Confirmed by Doctor
                    @elseif ($appointment->status === \App\Enums\AppointmentStatus::Confirmed)
                        Started by Doctor
                    @elseif ($appointment->status === \App\Enums\AppointmentStatus::InProgress)
                        Completion confirmed by Doctor &amp; Patient
                    @else
                        No further action
                    @endif
                </strong>
            </div>
        </section>
    </div>

    {{-- Reschedule Modal --}}
    <div id="reschedule-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Reschedule appointment</h2>
                    <p>Select a new date and time for #{{ $appointment->appointment_code }}.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('reschedule-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.appointments.reschedule', $appointment) }}">
                @csrf
                @method('PATCH')
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>New date *</span>
                            <input type="date" name="appointment_date" value="{{ $appointment->appointment_date->toDateString() }}" required>
                        </label>
                        <label class="field">
                            <span>New time *</span>
                            <select name="start_time" required>
                                @foreach (['08:00:00'=>'8:00 AM','08:30:00'=>'8:30 AM','09:00:00'=>'9:00 AM','09:30:00'=>'9:30 AM','10:00:00'=>'10:00 AM','10:30:00'=>'10:30 AM','11:00:00'=>'11:00 AM','11:30:00'=>'11:30 AM','13:00:00'=>'1:00 PM','13:30:00'=>'1:30 PM','14:00:00'=>'2:00 PM','15:00:00'=>'3:00 PM'] as $tVal => $tLabel)
                                    <option value="{{ $tVal }}" @selected($appointment->start_time === $tVal)>{{ $tLabel }}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('reschedule-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Save new time</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Cancel Modal --}}
    <div id="cancel-modal" class="modal-layer" hidden>
        <div class="modal confirm-modal">
            <div class="danger-icon"><x-clinic-icon name="alert" /></div>
            <h2>Cancel appointment?</h2>
            <p>This will notify {{ $appointment->patient->full_name }} and release the time slot.</p>
            <form method="POST" action="{{ route('admin.appointments.cancel', $appointment) }}">
                @csrf
                @method('PATCH')
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
                    <button type="button" class="btn secondary" onclick="closeModal('cancel-modal')">Keep appointment</button>
                    <button type="submit" class="btn danger">Cancel appointment</button>
                </div>
            </form>
        </div>
    </div>
@endsection
