@extends('layouts.admin')

@section('title', 'Reports & Insights')
@section('topbar-title', 'Appointment Reports')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Reports &amp; Insights</h2>
            <p>Review clinic activity, outcomes, and workload.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn secondary" onclick="window.print()">
                <x-clinic-icon name="print" size="16" /><span>Print</span>
            </button>
        </div>
    </div>

    <div class="report-switch">
        <a href="{{ route('admin.reports.index', ['type' => 'appointments', 'from' => $from, 'to' => $to, 'status' => $statusFilter]) }}" class="{{ $reportType === 'appointments' ? 'active' : '' }}">
            <x-clinic-icon name="calendar" />
            <span>
                <strong>Appointment report</strong>
                <small>Visits by date and status</small>
            </span>
        </a>
        <a href="{{ route('admin.reports.index', ['type' => 'workload', 'from' => $from, 'to' => $to, 'status' => $statusFilter]) }}" class="{{ $reportType === 'workload' ? 'active' : '' }}">
            <x-clinic-icon name="doctor" />
            <span>
                <strong>Doctor workload</strong>
                <small>Practitioner visit summary</small>
            </span>
        </a>
    </div>

    <form method="GET" action="{{ route('admin.reports.index') }}" class="card report-filter">
        <input type="hidden" name="type" value="{{ $reportType }}">
        <div>
            <label class="field">
                <span>From</span>
                <input type="date" name="from" value="{{ $from }}">
            </label>
            <label class="field">
                <span>To</span>
                <input type="date" name="to" value="{{ $to }}">
            </label>
            <label class="field">
                <span>Status</span>
                <select name="status">
                    <option value="all">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected($statusFilter === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </label>
        </div>
        <button type="submit" class="btn primary">Apply filters</button>
    </form>

    <div class="report-stats">
        <section class="card">
            <span>Total appointments</span>
            <strong>{{ $total }}</strong>
            <small>{{ \Carbon\CarbonImmutable::parse($from)->format('M j') }}–{{ \Carbon\CarbonImmutable::parse($to)->format('M j, Y') }}</small>
        </section>
        <section class="card">
            <span>{{ $reportType === 'appointments' ? 'Completion rate' : 'Avg. per doctor' }}</span>
            <strong>{{ $reportType === 'appointments' ? $completionRate.'%' : $avgPerDoctor }}</strong>
            <small class="up">Active period metrics</small>
        </section>
        <section class="card">
            <span>{{ $reportType === 'appointments' ? 'Cancelled' : 'Clinical hours' }}</span>
            <strong>{{ $reportType === 'appointments' ? $cancelled : $clinicalHours.'h' }}</strong>
            <small>{{ $total > 0 ? round(($cancelled / $total) * 100, 1) : 0 }}% cancellation rate</small>
        </section>
        <section class="card">
            <span>{{ $reportType === 'appointments' ? 'No-show rate' : 'Patients seen' }}</span>
            <strong>{{ $reportType === 'appointments' ? $noShowRate.'%' : $uniquePatients }}</strong>
            <small class="up">Within target</small>
        </section>
    </div>

    <div class="report-grid">
        <section class="card donut-card">
            <div class="card-head">
                <div>
                    <h3>{{ $reportType === 'appointments' ? 'Appointments by status' : 'Status distribution' }}</h3>
                    <p>Distribution for selected period</p>
                </div>
            </div>
            <div class="donut-wrap">
                <div class="donut" style="background: conic-gradient(#3b9c75 0 {{ $donutStops['greenEnd'] }}%, #318995 {{ $donutStops['greenEnd'] }}% {{ $donutStops['tealEnd'] }}%, #d55a55 {{ $donutStops['tealEnd'] }}% {{ $donutStops['redEnd'] }}%, #d89b32 {{ $donutStops['redEnd'] }}%);">
                    <span><strong>{{ $total }}</strong>Total</span>
                </div>
                <div class="donut-legend">
                    <div><span><i class="green"></i>Completed</span><strong>{{ $completed }}</strong></div>
                    <div><span><i class="teal"></i>Confirmed</span><strong>{{ $confirmed }}</strong></div>
                    <div><span><i class="red"></i>Cancelled</span><strong>{{ $cancelled }}</strong></div>
                    <div><span><i class="amber"></i>No-show</span><strong>{{ $noShow }}</strong></div>
                </div>
            </div>
        </section>

        <section class="card report-table">
            <div class="card-head">
                <div>
                    <h3>{{ $reportType === 'appointments' ? 'Daily appointment summary' : 'Doctor visit summary' }}</h3>
                    <p>Detailed performance breakdown</p>
                </div>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                    <tr>
                        <th>{{ $reportType === 'appointments' ? 'Date' : 'Doctor' }}</th>
                        <th>Scheduled</th>
                        <th>Completed</th>
                        <th>Cancelled</th>
                        <th>Rate</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($tableRows as $row)
                        <tr>
                            <td><strong>{{ $row['label'] }}</strong></td>
                            <td>{{ $row['scheduled'] }}</td>
                            <td>{{ $row['completed'] }}</td>
                            <td>{{ $row['cancelled'] }}</td>
                            <td><strong>{{ $row['rate'] }}%</strong></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:24px; color:var(--muted);">No appointments in this range.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection
