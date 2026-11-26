@extends('layouts.master')

@php
    $rows = [
        ['Anna Reyes', 'AR', 'anna@cliniccare.com', 'Administrator', 'Active', 'blue', 'Today, 9:42 AM'],
        ['Dr. Elena Morris', 'EM', 'elena@cliniccare.com', 'Doctor', 'Active', 'teal', 'Today, 9:42 AM'],
        ['Mia Brooks', 'MB', 'mia@cliniccare.com', 'Receptionist', 'Active', 'purple', 'Today, 9:42 AM'],
        ['Dr. Marcus Lee', 'ML', 'marcus@cliniccare.com', 'Doctor', 'Inactive', 'amber', 'May 30, 2025'],
    ];
@endphp

@section('title', 'User Management — ClinicCare')
@section('page-title', 'User Management')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ modal: false, search: '' }">
    <div class="page-header">
        <div>
            <h2>User Management</h2>
            <p>Control access, roles, and account status.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" @click="modal = true">
                <x-clinic-icon name="plus" :size="17" /><span>Add user</span>
            </button>
        </div>
    </div>

    <section class="card table-card">
        <div class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" :size="18" />
                <input x-model="search" aria-label="Search users..." placeholder="Search users...">
            </div>
            <div class="filters">
                <select><option>All roles</option><option>Administrator</option><option>Doctor</option></select>
                <select><option>All statuses</option></select>
            </div>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Last active</th>
                        <th>Status</th>
                        <th>Access</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rows as [$name, $initials, $email, $role, $status, $tone, $lastActive])
                        <tr x-show="!search || '{{ strtolower($name . ' ' . $email . ' ' . $role) }}'.includes(search.toLowerCase())">
                            <td>
                                <div class="person-cell">
                                    <span class="avatar {{ $tone }} md">{{ $initials }}</span>
                                    <span><strong>{{ $name }}</strong><small>{{ $email }}</small></span>
                                </div>
                            </td>
                            <td><span class="role-tag">{{ $role }}</span></td>
                            <td>{{ $lastActive }}</td>
                            <td><span class="simple-status {{ strtolower($status) }}">{{ $status }}</span></td>
                            <td><button type="button" class="toggle {{ $status === 'Active' ? 'on' : '' }}"><i></i></button></td>
                            <td><button type="button" class="icon-btn"><x-clinic-icon name="more" /></button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span>Showing 1–4 of 12 users</span>
            <div>
                <button type="button" disabled>‹</button>
                <button type="button" class="active">1</button>
                <button type="button">2</button>
                <button type="button">›</button>
            </div>
        </div>
    </section>

    <div class="modal-layer" x-show="modal" x-cloak @click.self="modal = false">
        <div class="modal form-modal">
            <div class="modal-head">
                <div><h2>Add a user</h2><p>Invite a team member to ClinicCare.</p></div>
                <button type="button" class="icon-btn" @click="modal = false"><x-clinic-icon name="close" /></button>
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <label class="field"><span>Full name</span><input type="text" placeholder="First and last name"></label>
                    <label class="field"><span>Email</span><input type="email" placeholder="name@cliniccare.com"></label>
                    <label class="field"><span>Role</span><select><option>Receptionist</option><option>Administrator</option><option>Doctor</option></select></label>
                    <label class="field"><span>Temporary password</span><input type="password" value="Welcome2025!"></label>
                </div>
                <div class="info-banner"><x-clinic-icon name="shield" /><span>The user will be asked to change their password on first login.</span></div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="modal = false">Cancel</button>
                <button type="button" class="btn primary" @click="modal = false">Send invitation</button>
            </div>
        </div>
    </div>
</div>
@endsection
