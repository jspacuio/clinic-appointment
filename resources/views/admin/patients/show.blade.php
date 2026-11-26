@extends('layouts.admin')

@section('title', $patient->full_name)
@section('topbar-title', 'Patient Profile')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <a href="{{ route('admin.patients.index') }}" class="back-link">
        <x-clinic-icon name="back" size="17" />Back to patients
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
        <button type="button" class="btn secondary" onclick="openModal('edit-patient-modal')">
            <x-clinic-icon name="edit" size="16" /><span>Edit patient</span>
        </button>
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
            <h3>Care summary</h3>
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
                <p>Previous consultations and upcoming visits</p>
            </div>
            <a href="{{ route('admin.appointments.create', ['patient_id' => $patient->id]) }}" class="btn secondary">
                <x-clinic-icon name="plus" size="16" /><span>Book appointment</span>
            </a>
        </div>

        @forelse ($visits as $visit)
            <div class="visit-row">
                <div class="date-box">
                    <strong>{{ $visit->appointment_date->format('d') }}</strong>
                    <span>{{ $visit->appointment_date->format('M') }}</span>
                </div>
                <div>
                    <strong>{{ $visit->reason ?: $visit->visit_type }}</strong>
                    <small>{{ $visit->doctor->full_name }} · {{ $visit->doctor->specialization }}</small>
                </div>
                <x-clinic-badge :status="$visit->status" />
                <a href="{{ route('admin.appointments.show', $visit) }}" class="icon-btn" aria-label="View appointment">
                    <x-clinic-icon name="chevron" />
                </a>
            </div>
        @empty
            <div style="padding:24px; text-align:center; color:var(--muted); font-size:12px;">No appointments recorded yet.</div>
        @endforelse
    </section>

    {{-- Edit Patient Modal --}}
    <div id="edit-patient-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Edit patient</h2>
                    <p>Update {{ $patient->full_name }} ({{ $patient->patient_code }}).</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('edit-patient-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.patients.update', $patient) }}">
                @csrf
                @method('PUT')
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required>
                        </label>
                        <label class="field">
                            <span>Date of birth</span>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->toDateString()) }}">
                        </label>
                        <label class="field">
                            <span>Sex *</span>
                            <select name="sex" required>
                                @foreach (['Female', 'Male', 'Prefer not to say'] as $sexOption)
                                    <option value="{{ $sexOption }}" @selected($patient->sex === $sexOption)>{{ $sexOption }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="field">
                            <span>Phone number *</span>
                            <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required>
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
                            <span>Allergy note</span>
                            <input type="text" name="allergy_note" value="{{ old('allergy_note', $patient->allergy_note) }}">
                        </label>
                        <label class="field">
                            <span>Status</span>
                            <select name="is_active">
                                <option value="1" @selected($patient->is_active)>Active</option>
                                <option value="0" @selected(! $patient->is_active)>Inactive</option>
                            </select>
                        </label>
                        <label class="field full">
                            <span>Home address</span>
                            <textarea name="address">{{ old('address', $patient->address) }}</textarea>
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('edit-patient-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Update patient</button>
                </div>
            </form>
        </div>
    </div>
@endsection
