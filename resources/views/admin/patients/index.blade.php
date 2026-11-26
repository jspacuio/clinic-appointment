@extends('layouts.admin')

@section('title', 'Patients')
@section('topbar-title', 'Patients')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Patients</h2>
            <p>Manage patient information and visit records.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" onclick="openModal('patient-modal')">
                <x-clinic-icon name="plus" size="17" /><span>Add Patient</span>
            </button>
        </div>
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('admin.patients.index') }}" class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" size="18" />
                <input type="search" name="search" value="{{ $search }}" placeholder="Search by name, ID, phone...">
            </div>
            <div class="filters">
                <select name="sex" aria-label="Gender filter">
                    <option value="all">All genders</option>
                    <option value="Female" @selected($sex === 'Female')>Female</option>
                    <option value="Male" @selected($sex === 'Male')>Male</option>
                    <option value="Prefer not to say" @selected($sex === 'Prefer not to say')>Prefer not to say</option>
                </select>
                <input type="date" name="registered_date" value="{{ $registeredDate }}" aria-label="Registration date">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Filters</span>
                </button>
            </div>
        </form>

        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>Patient</th>
                    <th>Patient ID</th>
                    <th>Age / Sex</th>
                    <th>Contact</th>
                    <th>Registered</th>
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($patients as $patient)
                    <tr>
                        <td>
                            <a href="{{ route('admin.patients.show', $patient) }}" class="person-cell">
                                <x-clinic-avatar :name="$patient->full_name" :tone="$patient->avatar_tone" />
                                <strong>{{ $patient->full_name }}</strong>
                            </a>
                        </td>
                        <td>{{ $patient->patient_code }}</td>
                        <td>{{ $patient->age() ?? '—' }} · {{ $patient->sex }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>{{ $patient->created_at?->format('M j, Y') }}</td>
                        <td>
                            <span class="simple-status {{ $patient->is_active ? 'active' : 'inactive' }}">
                                {{ $patient->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('admin.patients.show', $patient) }}" aria-label="View patient">
                                    <x-clinic-icon name="chevron" size="17" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:32px; color:var(--muted);">No patients found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $patients->links() }}
    </section>

    {{-- Add Patient Modal --}}
    <div id="patient-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Add new patient</h2>
                    <p>Create a patient record for the clinic.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('patient-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.patients.store') }}">
                @csrf
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Maya Anderson" required>
                        </label>
                        <label class="field">
                            <span>Date of birth</span>
                            <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        </label>
                        <label class="field">
                            <span>Sex *</span>
                            <select name="sex" required>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </label>
                        <label class="field">
                            <span>Phone number *</span>
                            <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(555) 000-0000" required>
                        </label>
                        <label class="field">
                            <span>Email address</span>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="patient@email.com">
                        </label>
                        <label class="field">
                            <span>Emergency contact</span>
                            <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Name and phone number">
                        </label>
                        <label class="field full">
                            <span>Allergy note</span>
                            <input type="text" name="allergy_note" value="{{ old('allergy_note') }}" placeholder="Optional allergy warning">
                        </label>
                        <label class="field full">
                            <span>Home address</span>
                            <textarea name="address" placeholder="Street, city, state, ZIP">{{ old('address') }}</textarea>
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('patient-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Save patient</button>
                </div>
            </form>
        </div>
    </div>
@endsection
