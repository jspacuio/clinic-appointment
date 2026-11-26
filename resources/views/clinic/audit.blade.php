@extends('layouts.master')

@php
    $logs = [
        ['Jun 16, 2025 · 9:42 AM', 'Anna Reyes', 'AR', 'blue', 'Updated appointment', 'Appointment #APT-2054', 'Pending → Confirmed'],
        ['Jun 16, 2025 · 9:18 AM', 'Dr. Elena Morris', 'EM', 'teal', 'Added consultation note', 'Patient · Sophia Carter', 'Empty → Clinical note added'],
        ['Jun 16, 2025 · 8:56 AM', 'Mia Brooks', 'MB', 'purple', 'Created patient', 'Patient · Liam Wilson', 'New record created'],
        ['Jun 15, 2025 · 4:34 PM', 'Anna Reyes', 'AR', 'blue', 'Changed user access', 'User · Marcus Lee', 'Active → Inactive'],
    ];
@endphp

@section('title', 'Audit Trail — ClinicCare')
@section('page-title', 'Audit Trail')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ search: '' }">
    <div class="page-header">
        <div>
            <h2>Audit Trail</h2>
            <p>A secure record of system activity and data changes.</p>
        </div>
    </div>

    <section class="card table-card">
        <div class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" :size="18" />
                <input x-model="search" aria-label="Search logs or entities..." placeholder="Search logs or entities...">
            </div>
            <div class="filters">
                <select><option>All users</option></select>
                <select><option>All actions</option></select>
                <input type="date">
            </div>
        </div>

        <div class="table-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Timestamp</th>
                        <th>User</th>
                        <th>Action</th>
                        <th>Entity</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as [$ts, $user, $initials, $tone, $action, $entity, $change])
                        <tr x-show="!search || '{{ strtolower($user . ' ' . $action . ' ' . $entity) }}'.includes(search.toLowerCase())">
                            <td>{{ $ts }}</td>
                            <td>
                                <div class="person-cell">
                                    <span class="avatar {{ $tone }} sm">{{ $initials }}</span>
                                    <strong>{{ $user }}</strong>
                                </div>
                            </td>
                            <td><strong>{{ $action }}</strong></td>
                            <td>{{ $entity }}</td>
                            <td><code>{{ $change }}</code></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="pagination">
            <span>Showing 1–4 of 1,284 events</span>
            <div>
                <button type="button" disabled>‹</button>
                <button type="button" class="active">1</button>
                <button type="button">2</button>
                <button type="button">3</button>
                <span>…</span>
                <button type="button">50</button>
                <button type="button">›</button>
            </div>
        </div>
    </section>
</div>
@endsection
