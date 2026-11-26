@extends('layouts.doctor')

@section('title', 'My Patients')
@section('topbar-title', 'My Patients')
@section('topbar-subtitle', $doctor->full_name.' · '.$doctor->specialization)

@section('content')
    <div class="page-header">
        <div>
            <h2>My Patients</h2>
            <p>Patients with consultations assigned to you.</p>
        </div>
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('doctor.patients.index') }}" class="table-tools">
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
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Filter</span>
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
                    <th>Status</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @forelse ($patients as $patient)
                    <tr>
                        <td>
                            <a href="{{ route('doctor.patients.show', $patient) }}" class="person-cell">
                                <x-clinic-avatar :name="$patient->full_name" :tone="$patient->avatar_tone" />
                                <strong>{{ $patient->full_name }}</strong>
                            </a>
                        </td>
                        <td>{{ $patient->patient_code }}</td>
                        <td>{{ $patient->age() ?? '—' }} · {{ $patient->sex }}</td>
                        <td>{{ $patient->phone }}</td>
                        <td>
                            <span class="simple-status {{ $patient->is_active ? 'active' : 'inactive' }}">
                                {{ $patient->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="row-actions">
                                <a href="{{ route('doctor.patients.show', $patient) }}" aria-label="View patient">
                                    <x-clinic-icon name="chevron" size="17" />
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:32px; color:var(--muted);">
                            No assigned patients found.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $patients->links() }}
    </section>
@endsection
