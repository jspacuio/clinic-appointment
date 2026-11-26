@extends('layouts.master')

@php
    $dates = ['Jun 16, 2025', 'Jun 15, 2025', 'Jun 14, 2025', 'Jun 13, 2025'];
    $doctorNames = ['Dr. Elena Morris', 'Dr. James Chen', 'Dr. Priya Shah', 'Dr. Marcus Lee'];
    $scheduled = [24, 18, 21, 22];
    $completed = [20, 16, 18, 19];
    $cancelled = [2, 1, 2, 1];
    $rates = [83, 89, 86, 86];
@endphp

@section('title', 'Reports & Insights — ClinicCare')
@section('page-title', 'Appointment Reports')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data="{ report: 'appointments' }">
    <div class="page-header">
        <div>
            <h2>Reports & Insights</h2>
            <p>Review clinic activity, outcomes, and workload.</p>
        </div>
        <div class="header-actions">
            <button type="button" class="btn secondary" onclick="window.print()"><x-clinic-icon name="print" :size="17" /><span>Print</span></button>
            <button type="button" class="btn secondary"><x-clinic-icon name="download" :size="17" /><span>Export PDF</span></button>
        </div>
    </div>

    <div class="report-switch">
        <button type="button" :class="{ 'active': report === 'appointments' }" @click="report = 'appointments'">
            <x-clinic-icon name="calendar" />
            <span><strong>Appointment report</strong><small>Visits by date and status</small></span>
        </button>
        <button type="button" :class="{ 'active': report === 'workload' }" @click="report = 'workload'">
            <x-clinic-icon name="doctor" />
            <span><strong>Doctor workload</strong><small>Practitioner visit summary</small></span>
        </button>
    </div>

    <section class="card report-filter">
        <div>
            <label class="field"><span>From</span><input type="date"></label>
            <label class="field"><span>To</span><input type="date"></label>
            <label class="field"><span>Status</span><select><option>All statuses</option></select></label>
        </div>
        <button type="button" class="btn primary">Apply filters</button>
    </section>

    <div class="report-stats">
        <section class="card">
            <span>Total appointments</span>
            <strong x-text="report === 'appointments' ? '486' : '382'"></strong>
            <small>Jun 1–30, 2025</small>
        </section>
        <section class="card">
            <span x-text="report === 'appointments' ? 'Completion rate' : 'Avg. per doctor'"></span>
            <strong x-text="report === 'appointments' ? '84.2%' : '95.5'"></strong>
            <small class="up">↑ 4.1% vs last period</small>
        </section>
        <section class="card">
            <span x-text="report === 'appointments' ? 'Cancelled' : 'Clinical hours'"></span>
            <strong x-text="report === 'appointments' ? '31' : '214h'"></strong>
            <small>6.4% of total</small>
        </section>
        <section class="card">
            <span x-text="report === 'appointments' ? 'No-show rate' : 'Patients seen'"></span>
            <strong x-text="report === 'appointments' ? '2.9%' : '354'"></strong>
            <small class="up">Within target</small>
        </section>
    </div>

    <div class="report-grid">
        <section class="card donut-card">
            <div class="card-head">
                <div>
                    <h3 x-text="report === 'appointments' ? 'Appointments by status' : 'Visits by doctor'"></h3>
                    <p>Distribution for selected period</p>
                </div>
            </div>
            <div class="donut-wrap">
                <div class="donut"><span><strong>486</strong>Total</span></div>
                <div class="donut-legend">
                    @foreach ([['Completed','409','green'],['Confirmed','28','teal'],['Cancelled','31','red'],['No-show','14','amber']] as [$label, $val, $tone])
                        <div><span><i class="{{ $tone }}"></i>{{ $label }}</span><strong>{{ $val }}</strong></div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="card report-table">
            <div class="card-head">
                <div>
                    <h3 x-text="report === 'appointments' ? 'Daily appointment summary' : 'Doctor visit summary'"></h3>
                    <p>Detailed performance breakdown</p>
                </div>
            </div>
            <div class="table-scroll">
                <table>
                    <thead>
                        <tr>
                            <th x-text="report === 'appointments' ? 'Date' : 'Doctor'"></th>
                            <th>Scheduled</th>
                            <th>Completed</th>
                            <th>Cancelled</th>
                            <th>Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 4; $i++)
                            <tr>
                                <td><strong x-text="report === 'appointments' ? '{{ $dates[$i] }}' : '{{ $doctorNames[$i] }}'"></strong></td>
                                <td>{{ $scheduled[$i] }}</td>
                                <td>{{ $completed[$i] }}</td>
                                <td>{{ $cancelled[$i] }}</td>
                                <td><strong>{{ $rates[$i] }}%</strong></td>
                            </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
@endsection
