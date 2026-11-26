@extends('layouts.admin')

@section('title', 'Audit Trail')
@section('topbar-title', 'Audit Trail')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Audit Trail</h2>
            <p>A secure record of system activity and data changes.</p>
        </div>
    </div>

    <section class="card table-card">
        <form method="GET" action="{{ route('admin.audit.index') }}" class="table-tools">
            <div class="search">
                <x-clinic-icon name="search" size="18" />
                <input type="search" name="search" value="{{ $search }}" placeholder="Search logs or entities...">
            </div>
            <div class="filters">
                <select name="user_id" aria-label="User filter">
                    <option value="">All users</option>
                    @foreach ($users as $u)
                        <option value="{{ $u->id }}" @selected($userId === $u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
                <select name="action" aria-label="Action filter">
                    <option value="all">All actions</option>
                    @foreach ($actions as $act)
                        <option value="{{ $act }}" @selected($selectedAction === $act)>{{ $act }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ $date }}" aria-label="Filter date">
                <button type="submit" class="btn secondary">
                    <x-clinic-icon name="filter" size="16" /><span>Filter</span>
                </button>
            </div>
        </form>

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
                @forelse ($logs as $log)
                    <tr>
                        <td>{{ $log->created_at?->format('M j, Y · g:i A') }}</td>
                        <td>
                            <div class="person-cell">
                                <x-clinic-avatar :name="$log->actor_name" tone="blue" size="sm" />
                                <strong>{{ $log->actor_name }}</strong>
                            </div>
                        </td>
                        <td><strong>{{ $log->action }}</strong></td>
                        <td>{{ $log->entity }}</td>
                        <td><code>{{ $log->change_summary }}</code></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:32px; color:var(--muted);">No audit events recorded yet.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{ $logs->links() }}
    </section>
@endsection
