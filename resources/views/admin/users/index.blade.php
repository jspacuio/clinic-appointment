@extends('layouts.admin')

@section('title', 'User Management')
@section('topbar-title', 'User Management')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>User Management</h2>
            <p>Control access, roles, and account status.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn primary" onclick="openModal('user-modal')">
                <x-clinic-icon name="plus" size="17" /><span>Add user</span>
            </button>
        </div>
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('admin.users.index') }}" class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" size="18" />
                <input type="search" name="search" value="{{ $search }}" placeholder="Search users...">
            </div>
            <div class="filters">
                <select name="role" aria-label="Role filter">
                    <option value="all">All roles</option>
                    @foreach ($roles as $r)
                        <option value="{{ $r->value }}" @selected($role === $r->value)>{{ $r->label() }}</option>
                    @endforeach
                </select>
                <select name="status" aria-label="Status filter">
                    <option value="all">All statuses</option>
                    <option value="active" @selected($status === 'active')>Active</option>
                    <option value="inactive" @selected($status === 'inactive')>Inactive</option>
                </select>
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Apply</span>
                </button>
            </div>
        </form>

        <div class="table-scroll">
            <table>
                <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th>Last active</th>
                    <th>Status</th>
                    <th>Access</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td>
                            <div class="person-cell">
                                <x-clinic-avatar :name="$u->name" :tone="$u->isDoctor() ? 'teal' : 'blue'" />
                                <span>
                                    <strong>{{ $u->name }}</strong>
                                    <small>{{ $u->email }}</small>
                                </span>
                            </div>
                        </td>
                        <td><span class="role-tag">{{ $u->role?->label() ?? 'User' }}</span></td>
                        <td>{{ $u->last_active_at?->diffForHumans() ?? 'Never' }}</td>
                        <td>
                            <span class="simple-status {{ $u->is_active ? 'active' : 'inactive' }}">
                                {{ $u->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.toggle', $u) }}">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="toggle {{ $u->is_active ? 'on' : '' }}" aria-label="Toggle access for {{ $u->name }}">
                                    <i></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </section>

    {{-- Add User Modal --}}
    <div id="user-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Add a user</h2>
                    <p>Invite a team member to ClinicCare.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('user-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="name" placeholder="First and last name" required>
                        </label>
                        <label class="field">
                            <span>Email *</span>
                            <input type="email" name="email" placeholder="name@cliniccare.com" required>
                        </label>
                        <label class="field">
                            <span>Role *</span>
                            <select name="role" required>
                                <option value="receptionist">Receptionist (Admin Portal)</option>
                                <option value="admin">Administrator (Admin Portal)</option>
                                <option value="doctor">Doctor (Doctor Portal)</option>
                                <option value="patient">Patient (Patient Portal)</option>
                            </select>
                        </label>
                        <label class="field">
                            <span>Temporary password *</span>
                            <input type="password" name="password" value="Welcome2025!" required>
                        </label>
                        <label class="field">
                            <span>Phone</span>
                            <input type="text" name="phone" placeholder="(555) 000-0000">
                        </label>
                        <label class="field">
                            <span>Specialization (if Doctor)</span>
                            <input type="text" name="specialization" placeholder="General Medicine">
                        </label>
                    </div>
                    <div class="info-banner">
                        <x-clinic-icon name="shield" />
                        <span>Administrators and Receptionists sign in at <strong>/admin/login</strong>; Doctors sign in at <strong>/doctor/login</strong>.</span>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('user-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Create user</button>
                </div>
            </form>
        </div>
    </div>
@endsection
