@extends('layouts.master')

@php
    $patientList = $patients->map(fn ($p) => [
        'id' => $p->id,
        'name' => $p->full_name,
        'initials' => $p->initials,
        'code' => $p->patient_code,
        'ageSex' => ($p->age !== null ? $p->age : '—') . ' · ' . ($p->sex ?: '—'),
        'phone' => $p->phone ?: ($p->email ?: 'No contact'),
        'tone' => $p->avatar_tone,
        'search' => strtolower($p->full_name . ' ' . $p->patient_code . ' ' . ($p->phone ?? '') . ' ' . ($p->email ?? '')),
    ])->values();

    $initialPatient = $patientList->firstWhere('id', (int) ($selectedPatientId ?? 0)) ?? $patientList->first();

    $doctors = [
        ['Dr. Elena Morris', 'General Medicine', 'EM', 'teal'],
        ['Dr. James Chen', 'Pediatrics', 'JC', 'blue'],
        ['Dr. Priya Shah', 'Dermatology', 'PS', 'purple'],
    ];
    $slots = ['8:00 AM','8:30 AM','9:00 AM','9:30 AM','10:00 AM','10:30 AM','11:00 AM','11:30 AM','1:00 PM','1:30 PM','2:00 PM','2:30 PM'];
    $dates = collect(range(0, 4))->map(fn ($i) => now()->addDays($i));
@endphp

@section('title', 'Book Appointment — ClinicCare')
@section('page-title', 'Book Appointment')
@section('page-subtitle', 'ClinicCare administration')

@section('content')
<div x-data='{
    step: 1,
    patients: @json($patientList),
    selectedPatient: @json($initialPatient),
    patientSearch: "",
    showQuickPatient: {{ $errors->any() ? "true" : "false" }},
    doctor: "Dr. Elena Morris",
    doctorSpec: "General Medicine",
    doctorInitials: "EM",
    doctorTone: "teal",
    selectedDateLabel: "{{ $dates->first()->format("l, F j, Y") }}",
    selectedDateValue: "{{ $dates->first()->format("Y-m-d") }}",
    time: "9:00 AM",
    reason: "Annual wellness checkup",
    conflict: false,
    get filteredPatients() {
        const q = this.patientSearch.trim().toLowerCase();
        if (!q) return this.patients;
        return this.patients.filter(p => p.search.includes(q));
    }
}'>
    <div class="page-header">
        <div>
            <h2>Book an appointment</h2>
            <p>Schedule a new patient visit in four simple steps.</p>
        </div>
    </div>

    @if (session('status'))
        <div class="info-banner" style="margin-bottom: 16px;">
            <x-clinic-icon name="check" />
            <span>{{ session('status') }}</span>
        </div>
    @endif

    <div class="stepper">
        @foreach (['Patient', 'Doctor', 'Date & time', 'Confirm'] as $i => $label)
            @php $num = $i + 1; @endphp
            <div :class="{ 'active': step === {{ $num }}, 'done': step > {{ $num }} }">
                <span>
                    <template x-if="step > {{ $num }}">
                        <x-clinic-icon name="check" :size="16" />
                    </template>
                    <template x-if="step <= {{ $num }}">
                        <span>{{ $num }}</span>
                    </template>
                </span>
                <div>
                    <strong>{{ $label }}</strong>
                    <small>Step {{ $num }}</small>
                </div>
                @if ($i < 3)<i></i>@endif
            </div>
        @endforeach
    </div>

    <section class="card booking-card">
        <div class="booking-section" x-show="step === 1">
            <div class="section-title">
                <span>1</span>
                <div><h2>Select a patient</h2><p>Find an existing patient or create a new record.</p></div>
            </div>
            <div class="patient-picker">
                <div class="search">
                    <x-clinic-icon name="search" :size="18" />
                    <input x-model="patientSearch" aria-label="Search patient by name, ID or phone..." placeholder="Search patient by name, ID or phone...">
                </div>

                <button type="button" class="new-patient" style="position: relative;" @click="showQuickPatient = true">
                    <span><x-clinic-icon name="plus" /></span>
                    <strong>Create new patient</strong>
                    <small>Add someone who is not yet registered</small>
                </button>

                <div class="info-banner" x-show="selectedPatient" style="margin-top: 0; margin-bottom: 12px; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 9px;">
                        <x-clinic-icon name="check" :size="16" />
                        <span>
                            Selected patient:
                            <strong x-text="selectedPatient ? selectedPatient.name : ''"></strong>
                            <span x-text="selectedPatient ? '(' + selectedPatient.code + ')' : ''"></span>
                        </span>
                    </div>
                    <span class="badge confirmed"><i></i>Selected</span>
                </div>

                <template x-for="p in filteredPatients" :key="p.id">
                    <button type="button"
                            class="picker-row"
                            :class="{ 'selected': selectedPatient && selectedPatient.id === p.id }"
                            @click="selectedPatient = p">
                        <span class="avatar md" :class="p.tone" x-text="p.initials"></span>
                        <div>
                            <strong x-text="p.name"></strong>
                            <small x-text="p.code + ' · ' + p.ageSex + ' · ' + p.phone"></small>
                        </div>
                        <span class="badge confirmed" x-show="selectedPatient && selectedPatient.id === p.id"><i></i>Selected</span>
                        <span class="radio"><i></i></span>
                    </button>
                </template>

                <div x-show="filteredPatients.length === 0" x-cloak style="padding: 20px; text-align: center; color: var(--muted); font-size: 12px;">
                    No matching patients found. Click <strong>Create new patient</strong> above to add one.
                </div>
            </div>
        </div>

        <div class="booking-section" x-show="step === 2" x-cloak>
            <div class="section-title">
                <span>2</span>
                <div><h2>Choose a doctor</h2><p>Select a specialty and available practitioner.</p></div>
            </div>
            <label class="field">
                <span>Specialization</span>
                <select><option>General Medicine</option><option>Pediatrics</option><option>Dermatology</option></select>
            </label>
            <div class="doctor-picker">
                @foreach ($doctors as [$name, $spec, $initials, $tone])
                    <button type="button"
                            class="doctor-option"
                            :class="{ 'selected': doctor === '{{ $name }}' }"
                            @click="doctor = '{{ $name }}'; doctorSpec = '{{ $spec }}'; doctorInitials = '{{ $initials }}'; doctorTone = '{{ $tone }}'">
                        <span class="avatar {{ $tone }} lg">{{ $initials }}</span>
                        <div>
                            <h3>{{ $name }}</h3>
                            <p>{{ $spec }}</p>
                            <span><i></i>Available today</span>
                        </div>
                        <span class="radio"><i></i></span>
                    </button>
                @endforeach
            </div>
        </div>

        <div class="booking-section" x-show="step === 3" x-cloak>
            <div class="section-title">
                <span>3</span>
                <div><h2>Pick date & time</h2><p>Unavailable times are disabled to prevent conflicts.</p></div>
            </div>
            <div class="date-strip">
                @foreach ($dates as $d)
                    @php
                        $fullLabel = $d->format('l, F j, Y');
                        $isoValue = $d->format('Y-m-d');
                    @endphp
                    <button type="button"
                            :class="{ 'selected': selectedDateValue === '{{ $isoValue }}' }"
                            @click="selectedDateLabel = '{{ $fullLabel }}'; selectedDateValue = '{{ $isoValue }}'">
                        <span>{{ $d->format('D') }}</span><span>{{ $d->format('j') }}</span>
                    </button>
                @endforeach
            </div>
            <h3 class="slot-title">Available times <small x-text="selectedDateLabel"></small></h3>
            <div class="time-slots">
                @foreach ($slots as $i => $slot)
                    @php $booked = in_array($i, [1, 5, 9]); @endphp
                    <button type="button"
                            @if($booked) disabled @endif
                            :class="{ 'selected': time === '{{ $slot }}' }"
                            @click="time = '{{ $slot }}'; conflict = ('{{ $slot }}' === '10:00 AM')">
                        {{ $slot }}
                        @if($booked)<small>Booked</small>@endif
                    </button>
                @endforeach
            </div>
            <div class="error-banner" x-show="conflict" x-cloak>
                <x-clinic-icon name="alert" />
                <span><strong>This time was just booked.</strong>Please choose another available slot.</span>
            </div>
        </div>

        <div class="booking-section" x-show="step === 4" x-cloak>
            <div class="section-title">
                <span><x-clinic-icon name="check" /></span>
                <div><h2>Review & confirm</h2><p>Check the details before creating this appointment.</p></div>
            </div>
            <div class="confirm-grid">
                <div class="confirm-person">
                    <span>Patient</span>
                    <div>
                        <span class="avatar md" :class="selectedPatient ? selectedPatient.tone : 'purple'" x-text="selectedPatient ? selectedPatient.initials : 'PT'"></span>
                        <div>
                            <strong x-text="selectedPatient ? selectedPatient.name : 'No patient selected'"></strong>
                            <small x-text="selectedPatient ? (selectedPatient.code + ' · ' + selectedPatient.phone) : ''"></small>
                        </div>
                    </div>
                </div>
                <div class="confirm-person">
                    <span>Doctor</span>
                    <div>
                        <span class="avatar md" :class="doctorTone" x-text="doctorInitials"></span>
                        <div><strong x-text="doctor"></strong><small x-text="doctorSpec"></small></div>
                    </div>
                </div>
                <div class="confirm-detail">
                    <x-clinic-icon name="calendar" />
                    <span><small>Date</small><strong x-text="selectedDateLabel"></strong></span>
                </div>
                <div class="confirm-detail">
                    <x-clinic-icon name="clock" />
                    <span><small>Time</small><strong x-text="time + ' · 30 minutes'"></strong></span>
                </div>
            </div>
            <label class="field">
                <span>Reason for visit</span>
                <textarea x-model="reason" placeholder="Describe the reason for this visit..."></textarea>
            </label>
            <div class="info-banner">
                <x-clinic-icon name="bell" />
                <span>A confirmation will be sent to the patient after booking.</span>
            </div>
        </div>

        <div class="booking-actions">
            <template x-if="step === 1">
                <a href="{{ route('dashboard') }}" class="btn secondary">Cancel</a>
            </template>
            <template x-if="step > 1">
                <button type="button" class="btn secondary" @click="step--">Back</button>
            </template>

            <template x-if="step < 4">
                <button type="button"
                        class="btn primary"
                        :disabled="step === 1 && !selectedPatient"
                        @click="step++">
                    <span>Continue</span>
                    <x-clinic-icon name="arrow" :size="17" />
                </button>
            </template>
            <template x-if="step === 4">
                <form method="POST" action="{{ route('clinic.appointments.store') }}">
                    @csrf
                    <input type="hidden" name="patient_id" :value="selectedPatient ? selectedPatient.id : ''">
                    <input type="hidden" name="doctor_name" :value="doctor">
                    <input type="hidden" name="specialty" :value="doctorSpec">
                    <input type="hidden" name="appointment_date" :value="selectedDateValue">
                    <input type="hidden" name="appointment_time" :value="time">
                    <input type="hidden" name="reason" :value="reason">
                    <button type="submit" class="btn primary" :disabled="!selectedPatient">
                        <x-clinic-icon name="check" :size="17" />
                        <span>Confirm appointment</span>
                    </button>
                </form>
            </template>
        </div>
    </section>

    <div class="modal-layer" x-show="showQuickPatient" x-cloak @click.self="showQuickPatient = false">
        <form method="POST" action="{{ route('clinic.patients.store') }}" class="modal form-modal">
            @csrf
            <input type="hidden" name="redirect_to" value="book">
            <div class="modal-head">
                <div>
                    <h2>Add new patient</h2>
                    <p>Save a new patient and select them for this appointment.</p>
                </div>
                <button type="button" class="icon-btn" @click="showQuickPatient = false" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <div class="form-body">
                <div class="form-grid">
                    <label class="field {{ $errors->has('full_name') ? 'has-error' : '' }}">
                        <span>Full name *</span>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" placeholder="e.g. Maya Anderson" required>
                        @error('full_name')<small><x-clinic-icon name="alert" :size="14" />{{ $message }}</small>@enderror
                    </label>
                    <label class="field">
                        <span>Date of birth</span>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth') }}">
                    </label>
                    <label class="field">
                        <span>Sex</span>
                        <select name="sex">
                            <option value="">Select sex</option>
                            <option value="Female" @selected(old('sex') === 'Female')>Female</option>
                            <option value="Male" @selected(old('sex') === 'Male')>Male</option>
                            <option value="Prefer not to say" @selected(old('sex') === 'Prefer not to say')>Prefer not to say</option>
                        </select>
                    </label>
                    <label class="field">
                        <span>Phone number</span>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(555) 000-0000">
                    </label>
                    <label class="field">
                        <span>Email address</span>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="patient@email.com">
                    </label>
                    <label class="field">
                        <span>Emergency contact</span>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="Name and phone number">
                    </label>
                    <label class="field full">
                        <span>Home address</span>
                        <textarea name="address" placeholder="Street, city, state, ZIP">{{ old('address') }}</textarea>
                    </label>
                </div>
            </div>
            <div class="modal-foot">
                <button type="button" class="btn secondary" @click="showQuickPatient = false">Cancel</button>
                <button type="submit" class="btn primary">Save & select patient</button>
            </div>
        </form>
    </div>
</div>
@endsection
