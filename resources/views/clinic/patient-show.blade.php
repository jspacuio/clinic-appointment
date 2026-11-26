@extends('layouts.master')

@section('title', $patient->full_name . ' — ClinicCare')
@section('page-title', 'Patient Profile')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ editForm: {{ $errors->any() ? 'true' : 'false' }} }">
    <a href="{{ route('clinic.patients') }}" class="back-link">
        <x-clinic-icon name="back" :size="17" />Back to patients
    </a>

    @if (session('status'))
        <div class="info-banner" style="margin-bottom: 16px;">
            <x-clinic-icon name="check" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <section class="card profile-banner">
        <div class="patient-focus">
            <span class="avatar {{ $patient->avatar_tone }} lg">{{ $patient->initials }}</span>
            <div>
                <div class="profile-title">
                    <h2>{{ $patient->full_name }}</h2>
                    <span class="simple-status {{ strtolower($patient->status) }}">{{ $patient->status }}</span>
                </div>
                <p>
                    Patient #{{ $patient->patient_code }}
                    @if ($patient->age !== null) · {{ $patient->age }} years @endif
                    @if ($patient->sex) · {{ $patient->sex }} @endif
                </p>
                <span>
                    {{ $patient->phone ?: 'No phone' }}
                    @if ($patient->email) · {{ $patient->email }} @endif
                </span>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="btn secondary" @click="editForm = true">
                <x-clinic-icon name="edit" :size="17" /><span>Edit patient</span>
            </button>
            <form method="POST" action="{{ route('clinic.patients.destroy', $patient) }}" onsubmit="return confirm('Remove this patient record?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn danger">Remove</button>
            </form>
        </div>
    </section>

    <div class="profile-grid">
        <section class="card">
            <h3>Patient information</h3>
            <dl class="details-list">
                <div><dt>Date of birth</dt><dd>{{ $patient->date_of_birth?->format('F j, Y') ?? 'Not recorded' }}</dd></div>
                <div><dt>Address</dt><dd>{{ $patient->address ?: 'Not recorded' }}</dd></div>
                <div><dt>Emergency contact</dt><dd>{{ $patient->emergency_contact ?: 'Not recorded' }}</dd></div>
                <div><dt>Registered</dt><dd>{{ $patient->created_at?->format('F j, Y') }}</dd></div>
            </dl>
        </section>

        <section class="card">
            <h3>Care summary</h3>
            <div class="summary-numbers">
                <div><strong>{{ $patient->appointments->count() }}</strong><span>Total visits</span></div>
                <div><strong>{{ $patient->appointments->whereIn('status', ['Pending', 'Confirmed', 'In Progress'])->count() }}</strong><span>Upcoming</span></div>
                <div><strong>{{ $patient->appointments->where('status', 'No-show')->count() }}</strong><span>No-shows</span></div>
            </div>
            <div class="notice">
                <x-clinic-icon name="alert" />
                <span>
                    <strong>{{ $patient->allergies ?: 'No known allergies recorded' }}</strong>
                    <small>Updated {{ $patient->updated_at?->format('M j, Y') }}</small>
                </span>
            </div>
        </section>
    </div>

    <section class="card visit-history">
        <div class="card-head">
            <div>
                <h3>Visit history</h3>
                <p>Previous consultations and care notes</p>
            </div>
            <a href="{{ route('clinic.book', ['patient_id' => $patient->id]) }}" class="btn secondary">
                <x-clinic-icon name="plus" :size="17" /><span>Book appointment</span>
            </a>
        </div>
        @forelse ($patient->appointments as $visit)
            <div class="visit-row">
                <div class="date-box">
                    <strong>{{ $visit->appointment_date?->format('d') }}</strong>
                    <span>{{ $visit->appointment_date?->format('M') }}</span>
                </div>
                <div>
                    <strong>{{ $visit->reason ?: $visit->visit_type }}</strong>
                    <small>{{ $visit->doctor_name }} · {{ $visit->specialty }} · {{ $visit->appointment_time }}</small>
                </div>
                <span class="badge {{ $visit->badge_class }}"><i></i>{{ $visit->status }}</span>
                <a href="{{ route('clinic.appointments.show', $visit) }}" class="icon-btn">
                    <x-clinic-icon name="chevron" />
                </a>
            </div>
        @empty
            <div style="padding: 24px 20px; color: var(--muted); font-size: 12px;">
                No appointments recorded for this patient yet.
            </div>
        @endforelse
    </section>

    <div class="modal-layer" x-show="editForm" x-cloak @click.self="editForm = false">
        <form method="POST" action="{{ route('clinic.patients.update', $patient) }}" class="modal form-modal">
            @csrf
            @method('PATCH')
            <div class="modal-head">
                <div>
                    <h2>Edit patient</h2>
                    <p>Update {{ $patient->full_name }} ({{ $patient->patient_code }}).</p>
                </div>
                <button type="button" class="icon-btn" @click="editForm = false" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <label class="field {{ $errors->has('full_name') ? 'has-error' : '' }}">
                        <span>Full name *</span>
                        <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required>
                    </label>
                    <label class="field">
                        <span>Date of birth</span>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->format('Y-m-d')) }}">
                    </label>
                    <label class="field">
                        <span>Sex</span>
                        <select name="sex">
                            <option value="">Select sex</option>
                            <option value="Female" @selected(old('sex', $patient->sex) === 'Female')>Female</option>
                            <option value="Male" @selected(old('sex', $patient->sex) === 'Male')>Male</option>
                            <option value="Prefer not to say" @selected(old('sex', $patient->sex) === 'Prefer not to say')>Prefer not to say</option>
                        </select>
                    </label>
                    <label class="field">
                        <span>Status</span>
                        <select name="status">
                            <option value="Active" @selected(old('status', $patient->status) === 'Active')>Active</option>
                            <option value="Inactive" @selected(old('status', $patient->status) === 'Inactive')>Inactive</option>
                        </select>
                    </label>
                    <label class="field">
                        <span>Phone number</span>
                        <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}">
                    </label>
                    <label class="field">
                        <span>Email address</span>
                        <input type="email" name="email" value="{{ old('email', $patient->email) }}">
                    </label>
                    <label class="field">
                        <span>Emergency contact</span>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}">
                    </label>
                    <label class="field">
                        <span>Known allergies</span>
                        <input type="text" name="allergies" value="{{ old('allergies', $patient->allergies) }}">
                    </label>
                    <label class="field full">
                        <span>Home address</span>
                        <textarea name="address">{{ old('address', $patient->address) }}</textarea>
                    </label>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="editForm = false">Cancel</button>
                <button type="submit" class="btn primary">Save changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
