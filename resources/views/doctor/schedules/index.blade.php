@extends('layouts.doctor')

@section('title', 'My Schedule')
@section('topbar-title', 'My Schedule')
@section('topbar-subtitle', $doctor->full_name.' · '.$doctor->specialization)

@section('content')
    <div class="page-header">
        <div>
            <h2>My Schedule</h2>
            <p>Set your available hours and manage blocked or booked times.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('doctor.schedules.index') }}" class="btn secondary">
                {{ $calendar['weekStart']->format('M j') }}–{{ $calendar['weekEnd']->format('j, Y') }}
            </a>
            <button type="button" class="btn primary" onclick="openModal('doctor-schedule-modal')">
                <x-clinic-icon name="plus" size="17" /><span>Add availability / block</span>
            </button>
        </div>
    </div>

    <section class="card calendar-card">
        <div class="calendar-nav">
            <a href="{{ route('doctor.schedules.index', ['week' => $calendar['weekStart']->subWeek()->toDateString()]) }}" class="icon-btn">&lsaquo;</a>
            <div>
                <h3>{{ $calendar['weekStart']->format('F j') }}–{{ $calendar['weekEnd']->format('j, Y') }}</h3>
                <p>Weekly availability · {{ $doctor->consultation_duration }} min slots</p>
            </div>
            <a href="{{ route('doctor.schedules.index', ['week' => $calendar['weekStart']->addWeek()->toDateString()]) }}" class="icon-btn">&rsaquo;</a>
            <a href="{{ route('doctor.schedules.index') }}" class="btn secondary">Today</a>
        </div>

        <div class="weekly-grid">
            <div class="week-head empty"></div>
            @foreach ($calendar['days'] as $day)
                <div class="week-head {{ $day['isToday'] ? 'today' : '' }}">
                    <strong>{{ $day['day'] }}</strong>
                    <span>{{ $day['num'] }}</span>
                </div>
            @endforeach

            @foreach ($calendar['times'] as $timeKey => $timeLabel)
                <div class="time-label">{{ $timeLabel }}</div>
                @foreach ($calendar['days'] as $day)
                    @php $slot = $calendar['grid'][$day['date']][$timeKey]; @endphp
                    @if ($slot['state'] === 'booked' && $slot['appointmentId'])
                        <a href="{{ route('doctor.appointments.show', $slot['appointmentId']) }}" class="slot booked">
                            <strong>{{ $slot['label'] }}</strong>
                            <small>{{ $slot['sub'] }}</small>
                        </a>
                    @elseif ($slot['state'] === 'blocked' && $slot['scheduleId'])
                        <form method="POST" action="{{ route('doctor.schedules.destroy', $slot['scheduleId']) }}" style="display:contents;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="slot blocked" title="Click to unblock">
                                <strong>{{ $slot['label'] }}</strong>
                                <small>Click to unblock</small>
                            </button>
                        </form>
                    @else
                        <button
                            type="button"
                            class="slot available"
                            onclick="openDoctorSlotModal('{{ $day['date'] }}', '{{ $timeKey }}')"
                        >
                            <strong>Available</strong>
                        </button>
                    @endif
                @endforeach
            @endforeach
        </div>
    </section>

    <div id="doctor-schedule-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Update my schedule</h2>
                    <p>Block time off or mark a slot available.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('doctor-schedule-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('doctor.schedules.store') }}">
                @csrf
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Date *</span>
                            <input type="date" name="schedule_date" id="doc-modal-date" value="{{ $calendar['days'][0]['date'] }}" required>
                        </label>
                        <label class="field">
                            <span>Start time *</span>
                            <select name="start_time" id="doc-modal-time" required>
                                @foreach ($hourlySlots as $timeKey => $timeLabel)
                                    <option value="{{ $timeKey }}">{{ $timeLabel }}</option>
                                @endforeach
                            </select>
                        </label>
                        <label class="field">
                            <span>Status *</span>
                            <select name="status" required>
                                <option value="blocked">Blocked</option>
                                <option value="available">Available</option>
                            </select>
                        </label>
                        <label class="field">
                            <span>Note</span>
                            <input type="text" name="note" placeholder="e.g. Rounds, Lunch break">
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('doctor-schedule-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Save slot</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    function openDoctorSlotModal(date, time) {
        document.getElementById('doc-modal-date').value = date;
        document.getElementById('doc-modal-time').value = time;
        openModal('doctor-schedule-modal');
    }
</script>
@endpush
