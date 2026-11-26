@extends('layouts.master')

@php
    $doctors = [
        ['Dr. Elena Morris', 'General Medicine', 'Mon–Fri · 8:00 AM–4:00 PM', 'EM', 'teal', 'Active', 18, 4],
        ['Dr. James Chen', 'Pediatrics', 'Mon, Tue, Thu · 9:00 AM–5:00 PM', 'JC', 'blue', 'Active', 14, 3],
        ['Dr. Priya Shah', 'Dermatology', 'Wed–Sat · 10:00 AM–6:00 PM', 'PS', 'purple', 'Active', 11, 2],
        ['Dr. Marcus Lee', 'Cardiology', 'Tue–Fri · 8:00 AM–3:00 PM', 'ML', 'amber', 'Inactive', 9, 0],
    ];
@endphp

@section('title', 'Doctors — ClinicCare')
@section('page-title', 'Doctors')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ modal: false, filter: 'All', search: '' }">
    <div class="page-header">
        <div>
            <h2>Doctors</h2>
            <p>Manage practitioners, specialties, and availability.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" @click="modal = true">
                <x-clinic-icon name="plus" :size="17" />
                <span>Add Doctor</span>
            </button>
        </div>
    </div>

    <div class="doctor-toolbar">
        <div class="search">
            <x-clinic-icon name="search" :size="18" />
            <input x-model="search" aria-label="Search doctors or specialty..." placeholder="Search doctors or specialty...">
        </div>
        <div class="segmented">
            <button type="button" :class="{ 'active': filter === 'All' }" @click="filter = 'All'">All doctors</button>
            <button type="button" :class="{ 'active': filter === 'Active' }" @click="filter = 'Active'">Active</button>
            <button type="button" :class="{ 'active': filter === 'Inactive' }" @click="filter = 'Inactive'">Inactive</button>
        </div>
    </div>

    <div class="doctor-grid">
        @foreach ($doctors as [$name, $specialty, $hours, $initials, $tone, $status, $total, $today])
            <section class="card doctor-card"
                     x-show="(filter === 'All' || filter === '{{ $status }}') && (!search || '{{ strtolower($name . ' ' . $specialty) }}'.includes(search.toLowerCase()))">
                <div class="doctor-top">
                    <span class="avatar {{ $tone }} lg">{{ $initials }}</span>
                    <div class="toggle-line">
                        <span>{{ $status }}</span>
                        <button type="button" class="toggle {{ $status === 'Active' ? 'on' : '' }}" aria-label="Toggle {{ $name }} status"><i></i></button>
                    </div>
                </div>
                <h3>{{ $name }}</h3>
                <span class="specialty">{{ $specialty }}</span>
                <div class="availability">
                    <x-clinic-icon name="clock" />
                    <div>
                        <small>Availability</small>
                        <strong>{{ $hours }}</strong>
                    </div>
                </div>
                <div class="doctor-metrics">
                    <span><strong>{{ $total }}</strong>appointments</span>
                    <span><strong>{{ $today }}</strong>today</span>
                </div>
                <div class="button-row">
                    <button type="button" class="btn secondary" @click="modal = true"><x-clinic-icon name="edit" :size="17" /><span>Edit</span></button>
                    <a href="{{ route('clinic.schedules') }}" class="btn ghost"><span>View schedule</span></a>
                </div>
            </section>
        @endforeach
    </div>

    <div class="modal-layer" x-show="modal" x-cloak @click.self="modal = false">
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Add doctor</h2>
                    <p>Create a practitioner profile and login.</p>
                </div>
                <button type="button" class="icon-btn" @click="modal = false" aria-label="Close"><x-clinic-icon name="close" /></button>
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <label class="field"><span>Full name</span><input type="text" placeholder="Dr. First Last"></label>
                    <label class="field">
                        <span>Specialization</span>
                        <select><option>Select specialization</option><option>General Medicine</option><option>Pediatrics</option><option>Dermatology</option></select>
                    </label>
                    <label class="field"><span>License number</span><input type="text" placeholder="MD-000000"></label>
                    <label class="field"><span>Phone</span><input type="text" placeholder="(555) 000-0000"></label>
                    <label class="field"><span>Email</span><input type="email" placeholder="doctor@cliniccare.com"></label>
                    <label class="field"><span>Consultation duration</span><input type="text" placeholder="30 minutes"></label>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="modal = false">Cancel</button>
                <button type="button" class="btn primary" @click="modal = false">Save doctor</button>
            </div>
        </div>
    </div>
</div>
@endsection
