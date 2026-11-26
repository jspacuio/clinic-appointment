@extends('layouts.master')

@section('title', 'Patients — ClinicCare')
@section('page-title', 'Patients')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ showForm: {{ $errors->any() ? 'true' : 'false' }} }">
    <div class="page-header">
        <div>
            <h2>Patients</h2>
            <p>Manage patient information and visit records.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" @click="showForm = true">
                <x-clinic-icon name="plus" :size="17" />
                <span>Add Patient</span>
            </button>
        </div>
    </div>

    @if (session('status'))
        <div class="info-banner" style="margin-bottom: 16px;">
            <x-clinic-icon name="check" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <section class="card table-card">
        <form method="GET" action="{{ route('clinic.patients') }}" class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" :size="18" />
                <input type="text"
                       name="search"
                       value="{{ $filters['search'] ?? '' }}"
                       aria-label="Search by name, ID, phone..."
                       placeholder="Search by name, ID, phone...">
            </div>
            <div class="filters">
                <select name="sex" aria-label="Gender filter" onchange="this.form.submit()">
                    <option value="">All genders</option>
                    <option value="Female" @selected(($filters['sex'] ?? '') === 'Female')>Female</option>
                    <option value="Male" @selected(($filters['sex'] ?? '') === 'Male')>Male</option>
                    <option value="Prefer not to say" @selected(($filters['sex'] ?? '') === 'Prefer not to say')>Prefer not to say</option>
                </select>
                <input name="registered_date"
                       value="{{ $filters['registered_date'] ?? '' }}"
                       aria-label="Registration date"
                       type="date"
                       onchange="this.form.submit()">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" :size="17" />
                    <span>Filters</span>
                </button>
                @if (!empty($filters['search']) || !empty($filters['sex']) || !empty($filters['registered_date']))
                    <a href="{{ route('clinic.patients') }}" class="btn ghost">Reset</a>
                @endif
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
                                <a href="{{ route('clinic.patients.show', $patient) }}" class="person-cell">
                                    <span class="avatar {{ $patient->avatar_tone }} md">{{ $patient->initials }}</span>
                                    <strong>{{ $patient->full_name }}</strong>
                                </a>
                            </td>
                            <td>{{ $patient->patient_code }}</td>
                            <td>
                                {{ $patient->age !== null ? $patient->age : '—' }}
                                · {{ $patient->sex ?: '—' }}
                            </td>
                            <td>{{ $patient->phone ?: ($patient->email ?: '—') }}</td>
                            <td>{{ $patient->created_at?->format('M d, Y') }}</td>
                            <td>
                                <span class="simple-status {{ strtolower($patient->status) }}">
                                    {{ $patient->status }}
                                </span>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a href="{{ route('clinic.patients.show', $patient) }}"
                                       class="icon-btn"
                                       aria-label="View {{ $patient->full_name }}">
                                        <x-clinic-icon name="chevron" />
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 36px 16px; color: var(--muted);">
                                No patients found. Click <strong>Add Patient</strong> to create the first record.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span>
                @if ($patients->total() > 0)
                    Showing {{ $patients->firstItem() }}–{{ $patients->lastItem() }} of {{ $patients->total() }} {{ Str::plural('patient', $patients->total()) }}
                @else
                    Showing 0 patients
                @endif
            </span>
            @if ($patients->hasPages())
                <div>
                    @if ($patients->onFirstPage())
                        <button type="button" disabled aria-label="Previous page">‹</button>
                    @else
                        <a href="{{ $patients->previousPageUrl() }}" aria-label="Previous page">‹</a>
                    @endif

                    @foreach ($patients->getUrlRange(1, $patients->lastPage()) as $page => $url)
                        @if ($page == $patients->currentPage())
                            <button type="button" class="active">{{ $page }}</button>
                        @else
                            <a href="{{ $url }}">{{ $page }}</a>
                        @endif
                    @endforeach

                    @if ($patients->hasMorePages())
                        <a href="{{ $patients->nextPageUrl() }}" aria-label="Next page">›</a>
                    @else
                        <button type="button" disabled aria-label="Next page">›</button>
                    @endif
                </div>
            @endif
        </div>
    </section>

    <div class="modal-layer" x-show="showForm" x-cloak @click.self="showForm = false">
        <form method="POST" action="{{ route('clinic.patients.store') }}" class="modal form-modal">
            @csrf
            <div class="modal-head">
                <div>
                    <h2>Add new patient</h2>
                    <p>Create a patient record for the clinic.</p>
                </div>
                <button type="button" class="icon-btn" @click="showForm = false" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <label class="field {{ $errors->has('full_name') ? 'has-error' : '' }}">
                        <span>Full name *</span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Maya Anderson" required>
                        @error('full_name')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field {{ $errors->has('date_of_birth') ? 'has-error' : '' }}">
                        <span>Date of birth</span>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                        @error('date_of_birth')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field {{ $errors->has('sex') ? 'has-error' : '' }}">
                        <span>Sex</span>
                        <select name="sex">
                            <option value="">Select sex</option>
                            <option value="Female" @selected(old('sex') === 'Female')>Female</option>
                            <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                            <option value="Prefer not to say" @selected(old('sex') === 'Prefer not to say')>Prefer not to say</option>
                        </select>
                        @error('sex')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field {{ $errors->has('phone') ? 'has-error' : '' }}">
                        <span>Phone number</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(555) 000-0000">
                        @error('phone')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <span>Email address</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="patient@email.com">
                        @error('email')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field {{ $errors->has('emergency_contact') ? 'has-error' : '' }}">
                        <span>Emergency contact</span>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Name and phone number">
                        @error('emergency_contact')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field full {{ $errors->has('allergies') ? 'has-error' : '' }}">
                        <span>Known allergies</span>
                        <input type="text" name="allergies" value="{{ old('allergies') }}" placeholder="e.g. Penicillin, None">
                        @error('allergies')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>

                    <label class="field full {{ $errors->has('address') ? 'has-error' : '' }}">
                        <span>Home address</span>
                        <textarea name="address" placeholder="Street, city, state, ZIP">{{ old('address') }}</textarea>
                        @error('address')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="showForm = false">Cancel</button>
                <button type="submit" class="btn primary">Save patient</button>
            </div>
        </form>
    </div>
</div>
@endsection
