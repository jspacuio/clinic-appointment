@extends('layouts.admin')

@section('title', 'Doctor Schedules')
@section('topbar-title', 'Doctor Schedules')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Doctor Schedules</h2>
            <p>Set available hours and manage blocked or booked times.</p>
        </div>
        <div class="header-actions">
            @if ($calendar !== null)
                <a href="{{ route('admin.schedules.index', ['doctor_id' => $selectedDoctor?->id, 'week' => now()->toDateString()]) }}" class="btn secondary">
                    {{ $calendar['weekStart']->format('M j') }}–{{ $calendar['weekEnd']->format('j, Y') }}
                </a>
            @endif
            <button type="button" class="btn primary" onclick="openModal('schedule-modal')">
                <x-clinic-icon name="plus" size="17" /><span>Add availability / block</span>
            </button>
        </div>
    </div>

    @if ($selectedDoctor !== null && $calendar !== null)
        <section class="card schedule-filter">
            <form method="GET" action="{{ route('admin.schedules.index') }}">
                <input type="hidden" name="week" value="{{ $calendar['weekStart']->toDateString() }}">
                <label class="field">
                    <span>Doctor</span>
                    <select name="doctor_id" onchange="this.form.submit()">
                        @foreach ($doctors as $doc)
                            <option value="{{ $doc->id }}" @selected($doc->id === $selectedDoctor->id)>{{ $doc->full_name }}</option>
                        @endforeach
                    </select>
                </label>
            </form>
            <div class="doctor-summary">
                <x-clinic-avatar :name="$selectedDoctor->full_name" :tone="$selectedDoctor->avatar_tone" />
                <span>
                    <strong>{{ $selectedDoctor->full_name }}</strong>
                    <small>{{ $selectedDoctor->specialization }} · {{ $selectedDoctor->consultation_duration }} min slots</small>
                </span>
            </div>
            <div class="schedule-legend">
                <span><i class="available"></i>Available</span>
                <span><i class="booked"></i>Booked</span>
                <span><i class="blocked"></i>Blocked</span>
            </div>
        </section>

        <section class="card calendar-card">
            <div class="calendar-nav">
                <a href="{{ route('admin.schedules.index', ['doctor_id' => $selectedDoctor->id, 'week' => $calendar['weekStart']->subWeek()->toDateString()]) }}" class="icon-btn">&lsaquo;</a>
                <div>
                    <h3>{{ $calendar['weekStart']->format('F j') }}–{{ $calendar['weekEnd']->format('j, Y') }}</h3>
                    <p>Weekly availability</p>
                </div>
                <a href="{{ route('admin.schedules.index', ['doctor_id' => $selectedDoctor->id, 'week' => $calendar['weekStart']->addWeek()->toDateString()]) }}" class="icon-btn">&rsaquo;</a>
                <a href="{{ route('admin.schedules.index', ['doctor_id' => $selectedDoctor->id]) }}" class="btn secondary">Today</a>
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
                            <a href="{{ route('admin.appointments.show', $slot['appointmentId']) }}" class="slot booked">
                                <strong>{{ $slot['label'] }}</strong>
                                <small>{{ $slot['sub'] }}</small>
                            </a>
                        @elseif ($slot['state'] === 'blocked' && $slot['scheduleId'])
                            <form method="POST" action="{{ route('admin.schedules.destroy', $slot['scheduleId']) }}" style="display:contents;">
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
                                onclick="openScheduleForSlot('{{ $day['date'] }}', '{{ $timeKey }}')"
                            >
                                <strong>Available</strong>
                            </button>
                        @endif
                    @endforeach
                @endforeach
            </div>
        </section>

        {{-- Schedule Slot Modal --}}
        <div id="schedule-modal" class="modal-layer" hidden>
            <div class="modal form-modal">
                <div class="modal-head">
                    <div>
                        <h2>Update schedule slot</h2>
                        <p>Mark a slot as available or blocked for {{ $selectedDoctor->full_name }}.</p>
                    </div>
                    <button type="button" class="icon-btn" onclick="closeModal('schedule-modal')" aria-label="Close">
                        <x-clinic-icon name="close" />
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.schedules.store') }}">
                    @csrf
                    <input type="hidden" name="doctor_id" value="{{ $selectedDoctor->id }}">
                    <div class="form-body">
                        <div class="form-grid">
                            <label class="field">
                                <span>Date *</span>
                                <input type="date" name="schedule_date" id="modal-schedule-date" value="{{ $calendar['days'][0]['date'] }}" required>
                            </label>
                            <label class="field">
                                <span>Start time *</span>
                                <select name="start_time" id="modal-schedule-time" required>
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
                                <input type="text" name="note" placeholder="e.g. Lunch break, Surgery">
                            </label>
                        </div>
                    </div>
                    <div class="modal-foot">
                        <button type="button" class="btn secondary" onclick="closeModal('schedule-modal')">Cancel</button>
                        <button type="submit" class="btn primary">Save slot</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
<script>
    function openScheduleForSlot(date, time) {
        document.getElementById('modal-schedule-date').value = date;
        document.getElementById('modal-schedule-time').value = time;
        openModal('schedule-modal');
    }
</script>
@endpush
