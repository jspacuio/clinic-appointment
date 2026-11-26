@extends('layouts.admin')

@section('title', 'Book Appointment')
@section('topbar-title', 'Book Appointment')
@section('topbar-subtitle', 'ClinicCare administration')

@section('content')
    <div class="page-header">
        <div>
            <h2>Book an appointment</h2>
            <p>Schedule a new patient visit in four simple steps.</p>
        </div>
    </div>

    <div class="stepper" id="booking-stepper">
        <div class="active" data-step-indicator="1">
            <span>1</span>
            <div><strong>Patient</strong><small>Step 1</small></div>
            <i></i>
        </div>
        <div data-step-indicator="2">
            <span>2</span>
            <div><strong>Doctor</strong><small>Step 2</small></div>
            <i></i>
        </div>
        <div data-step-indicator="3">
            <span>3</span>
            <div><strong>Date &amp; time</strong><small>Step 3</small></div>
            <i></i>
        </div>
        <div data-step-indicator="4">
            <span>4</span>
            <div><strong>Confirm</strong><small>Step 4</small></div>
        </div>
    </div>

    <section class="card booking-card">
        <form method="POST" action="{{ route('admin.appointments.store') }}" id="booking-form">
            @csrf
            <input type="hidden" name="patient_id" id="input-patient-id" value="{{ old('patient_id', $selectedPatientId) }}">
            <input type="hidden" name="doctor_id" id="input-doctor-id" value="{{ old('doctor_id', $selectedDoctor?->id) }}">
            <input type="hidden" name="appointment_date" id="input-date" value="{{ old('appointment_date', $selectedDate) }}">
            <input type="hidden" name="start_time" id="input-time" value="{{ old('start_time', collect($slots)->firstWhere('disabled', false)['time'] ?? '') }}">
            <input type="hidden" name="visit_type" id="input-visit-type" value="{{ old('visit_type', 'General Checkup') }}">

            {{-- Step 1: Patient --}}
            <div class="booking-section" data-step-panel="1">
                <div class="section-title">
                    <span>1</span>
                    <div>
                        <h2>Select a patient</h2>
                        <p>Find an existing patient or create a new record.</p>
                    </div>
                </div>
                <div class="patient-picker">
                    <div class="search">
                        <x-clinic-icon name="search" size="18" />
                        <input type="search" id="patient-search-input" placeholder="Search patient by name, ID or phone...">
                    </div>

                    <button type="button" class="new-patient" onclick="openModal('quick-patient-modal')">
                        <span><x-clinic-icon name="plus" /></span>
                        <div>
                            <strong>Create new patient</strong>
                            <small>Add someone who is not yet registered</small>
                        </div>
                    </button>

                    <div id="patient-picker-list">
                        @foreach ($patients as $patient)
                            <button
                                type="button"
                                class="picker-row {{ (int) old('patient_id', $selectedPatientId) === $patient->id ? 'selected' : '' }}"
                                data-patient-id="{{ $patient->id }}"
                                data-patient-name="{{ $patient->full_name }}"
                                data-patient-meta="{{ $patient->patient_code }} · {{ $patient->phone }}"
                                onclick="selectPatient(this)"
                            >
                                <x-clinic-avatar :name="$patient->full_name" :tone="$patient->avatar_tone" />
                                <div>
                                    <strong>{{ $patient->full_name }}</strong>
                                    <small>{{ $patient->patient_code }} · {{ $patient->age() ?? '—' }} · {{ $patient->sex }} · {{ $patient->phone }}</small>
                                </div>
                                <span class="radio"><i></i></span>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Step 2: Doctor --}}
            <div class="booking-section" data-step-panel="2" hidden>
                <div class="section-title">
                    <span>2</span>
                    <div>
                        <h2>Choose a doctor</h2>
                        <p>Select a specialty and available practitioner.</p>
                    </div>
                </div>

                <label class="field">
                    <span>Specialization</span>
                    <select id="specialization-filter" onchange="filterDoctorsBySpecialty(this.value)">
                        <option value="all">All specializations</option>
                        @foreach ($specializations as $spec)
                            <option value="{{ $spec }}">{{ $spec }}</option>
                        @endforeach
                    </select>
                </label>

                <div class="doctor-picker">
                    @foreach ($doctors as $doc)
                        <button
                            type="button"
                            class="doctor-option {{ (int) old('doctor_id', $selectedDoctor?->id) === $doc->id ? 'selected' : '' }}"
                            data-doctor-id="{{ $doc->id }}"
                            data-doctor-name="{{ $doc->full_name }}"
                            data-doctor-spec="{{ $doc->specialization }}"
                            data-doctor-duration="{{ $doc->consultation_duration }}"
                            onclick="selectDoctor(this)"
                        >
                            <x-clinic-avatar :name="$doc->full_name" :tone="$doc->avatar_tone" size="lg" />
                            <div>
                                <h3>{{ $doc->full_name }}</h3>
                                <p>{{ $doc->specialization }}</p>
                                <span><i></i>Available · {{ $doc->consultation_duration }} min</span>
                            </div>
                            <span class="radio"><i></i></span>
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Step 3: Date & Time --}}
            <div class="booking-section" data-step-panel="3" hidden>
                <div class="section-title">
                    <span>3</span>
                    <div>
                        <h2>Pick date &amp; time</h2>
                        <p>Unavailable times are disabled to prevent conflicts.</p>
                    </div>
                </div>

                <div class="date-strip">
                    @foreach ($dates as $d)
                        <button
                            type="button"
                            class="{{ $selectedDate === $d['value'] ? 'selected' : '' }}"
                            data-date-value="{{ $d['value'] }}"
                            data-date-full="{{ $d['full'] }}"
                            onclick="selectDate(this)"
                        >
                            <span>{{ $d['day'] }}</span>
                            <span>{{ $d['num'] }}</span>
                        </button>
                    @endforeach
                </div>

                <h3 class="slot-title">
                    Available times <small id="selected-date-label">{{ collect($dates)->firstWhere('value', $selectedDate)['full'] ?? '' }}</small>
                </h3>

                <div class="time-slots" id="time-slots-grid">
                    @php $firstSelected = false; @endphp
                    @foreach ($slots as $slot)
                        @php
                            $isSelected = ! $slot['disabled'] && ! $firstSelected && (old('start_time') ? old('start_time') === $slot['time'] : true);
                            if ($isSelected) { $firstSelected = true; }
                        @endphp
                        <button
                            type="button"
                            class="{{ $isSelected ? 'selected' : '' }}"
                            data-time-value="{{ $slot['time'] }}"
                            data-time-label="{{ $slot['label'] }}"
                            @disabled($slot['disabled'])
                            onclick="selectTimeSlot(this)"
                        >
                            {{ $slot['label'] }}
                            @if ($slot['disabled'] && $slot['reason'])
                                <small>{{ $slot['reason'] }}</small>
                            @endif
                        </button>
                    @endforeach
                </div>

                @if ($errors->has('start_time'))
                    <div class="error-banner">
                        <x-clinic-icon name="alert" />
                        <span>
                            <strong>Conflict detected</strong>
                            {{ $errors->first('start_time') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Step 4: Confirm --}}
            <div class="booking-section" data-step-panel="4" hidden>
                <div class="section-title">
                    <span><x-clinic-icon name="check" size="18" /></span>
                    <div>
                        <h2>Review &amp; confirm</h2>
                        <p>Check the details before creating this appointment.</p>
                    </div>
                </div>

                <div class="confirm-grid">
                    <div class="confirm-person">
                        <span>Patient</span>
                        <div>
                            <div>
                                <strong id="confirm-patient-name">{{ $patients->firstWhere('id', $selectedPatientId)?->full_name ?? 'Select patient' }}</strong>
                                <small id="confirm-patient-meta">{{ $patients->firstWhere('id', $selectedPatientId)?->patient_code ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="confirm-person">
                        <span>Doctor</span>
                        <div>
                            <div>
                                <strong id="confirm-doctor-name">{{ $selectedDoctor?->full_name ?? 'Select doctor' }}</strong>
                                <small id="confirm-doctor-spec">{{ $selectedDoctor?->specialization ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="confirm-detail">
                        <x-clinic-icon name="calendar" />
                        <span>
                            <small>Date</small>
                            <strong id="confirm-date-label">{{ collect($dates)->firstWhere('value', $selectedDate)['full'] ?? $selectedDate }}</strong>
                        </span>
                    </div>
                    <div class="confirm-detail">
                        <x-clinic-icon name="clock" />
                        <span>
                            <small>Time</small>
                            <strong id="confirm-time-label">{{ collect($slots)->firstWhere('disabled', false)['label'] ?? '9:00 AM' }} · {{ $selectedDoctor?->consultation_duration ?? 30 }} minutes</strong>
                        </span>
                    </div>
                </div>

                <div class="form-grid" style="margin-bottom:14px;">
                    <label class="field">
                        <span>Visit type</span>
                        <select onchange="document.getElementById('input-visit-type').value = this.value">
                            <option value="General Checkup">General Checkup</option>
                            <option value="Follow-up">Follow-up</option>
                            <option value="Consultation">Consultation</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="Pediatrics">Pediatrics</option>
                        </select>
                    </label>
                </div>

                <label class="field">
                    <span>Reason for visit</span>
                    <textarea name="reason" placeholder="Describe symptoms or purpose of visit...">{{ old('reason', 'Annual wellness checkup') }}</textarea>
                </label>

                <div class="info-banner">
                    <x-clinic-icon name="bell" />
                    <span>The appointment will be created as Pending and will require confirmation from both the doctor and the patient.</span>
                </div>
            </div>

            <div class="booking-actions">
                <button type="button" class="btn secondary" id="btn-back" onclick="prevStep()">Cancel</button>
                <button type="button" class="btn primary" id="btn-next" onclick="nextStep()">
                    <span>Continue</span><x-clinic-icon name="arrow" size="16" />
                </button>
                <button type="submit" class="btn primary" id="btn-submit" hidden>
                    <x-clinic-icon name="check" size="16" /><span>Confirm appointment</span>
                </button>
            </div>
        </form>
    </section>

    {{-- Quick Create Patient Modal --}}
    <div id="quick-patient-modal" class="modal-layer" hidden>
        <div class="modal form-modal">
            <div class="modal-head">
                <div>
                    <h2>Add new patient</h2>
                    <p>Create a patient record for the clinic.</p>
                </div>
                <button type="button" class="icon-btn" onclick="closeModal('quick-patient-modal')" aria-label="Close">
                    <x-clinic-icon name="close" />
                </button>
            </div>
            <form method="POST" action="{{ route('admin.patients.store') }}">
                @csrf
                <input type="hidden" name="redirect_to" value="book">
                <div class="form-body">
                    <div class="form-grid">
                        <label class="field">
                            <span>Full name *</span>
                            <input type="text" name="full_name" placeholder="e.g. Maya Anderson" required>
                        </label>
                        <label class="field">
                            <span>Date of birth</span>
                            <input type="date" name="date_of_birth">
                        </label>
                        <label class="field">
                            <span>Sex *</span>
                            <select name="sex" required>
                                <option value="Female">Female</option>
                                <option value="Male">Male</option>
                                <option value="Prefer not to say">Prefer not to say</option>
                            </select>
                        </label>
                        <label class="field">
                            <span>Phone number *</span>
                            <input type="text" name="phone" placeholder="(555) 000-0000" required>
                        </label>
                        <label class="field">
                            <span>Email address</span>
                            <input type="email" name="email" placeholder="patient@email.com">
                        </label>
                        <label class="field">
                            <span>Emergency contact</span>
                            <input type="text" name="emergency_contact" placeholder="Name and phone number">
                        </label>
                        <label class="field full">
                            <span>Home address</span>
                            <textarea name="address" placeholder="Street, city, state, ZIP"></textarea>
                        </label>
                    </div>
                </div>
                <div class="modal-foot">
                    <button type="button" class="btn secondary" onclick="closeModal('quick-patient-modal')">Cancel</button>
                    <button type="submit" class="btn primary">Save patient</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    let currentStep = {{ $errors->has('start_time') ? 3 : 1 }};

    function renderStep() {
        document.querySelectorAll('[data-step-panel]').forEach(el => {
            el.hidden = Number(el.dataset.stepPanel) !== currentStep;
        });
        document.querySelectorAll('[data-step-indicator]').forEach(el => {
            const s = Number(el.dataset.stepIndicator);
            el.classList.toggle('active', s === currentStep);
            el.classList.toggle('done', s < currentStep);
        });
        document.getElementById('btn-back').textContent = currentStep === 1 ? 'Cancel' : 'Back';
        document.getElementById('btn-next').hidden = currentStep === 4;
        document.getElementById('btn-submit').hidden = currentStep !== 4;
    }

    function nextStep() {
        if (currentStep < 4) {
            currentStep++;
            renderStep();
        }
    }

    function prevStep() {
        if (currentStep === 1) {
            window.location.href = "{{ route('admin.dashboard') }}";
            return;
        }
        currentStep--;
        renderStep();
    }

    function selectPatient(btn) {
        document.querySelectorAll('#patient-picker-list .picker-row').forEach(r => r.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('input-patient-id').value = btn.dataset.patientId;
        document.getElementById('confirm-patient-name').textContent = btn.dataset.patientName;
        document.getElementById('confirm-patient-meta').textContent = btn.dataset.patientMeta;
    }

    function selectDoctor(btn) {
        document.querySelectorAll('.doctor-option').forEach(r => r.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('input-doctor-id').value = btn.dataset.doctorId;
        document.getElementById('confirm-doctor-name').textContent = btn.dataset.doctorName;
        document.getElementById('confirm-doctor-spec').textContent = btn.dataset.doctorSpec;
    }

    function filterDoctorsBySpecialty(spec) {
        document.querySelectorAll('.doctor-option').forEach(card => {
            card.hidden = spec !== 'all' && card.dataset.doctorSpec !== spec;
        });
    }

    function selectDate(btn) {
        const doctorId = document.getElementById('input-doctor-id').value;
        const dateVal = btn.dataset.dateValue;
        window.location.href = "{{ route('admin.appointments.create') }}?doctor_id=" + encodeURIComponent(doctorId)
            + "&patient_id=" + encodeURIComponent(document.getElementById('input-patient-id').value)
            + "&date=" + encodeURIComponent(dateVal);
    }

    function selectTimeSlot(btn) {
        document.querySelectorAll('#time-slots-grid button').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('input-time').value = btn.dataset.timeValue;
        const dur = document.querySelector('.doctor-option.selected')?.dataset.doctorDuration || '30';
        document.getElementById('confirm-time-label').textContent = btn.dataset.timeLabel + ' · ' + dur + ' minutes';
    }

    document.getElementById('patient-search-input')?.addEventListener('input', function (e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('#patient-picker-list .picker-row').forEach(row => {
            row.hidden = !row.textContent.toLowerCase().includes(q);
        });
    });

    renderStep();
</script>
@endpush
