@extends('layouts.patient')

@section('title', 'Book Appointment')
@section('topbar-title', 'Book Appointment')
@section('topbar-subtitle', 'Choose your medical concern and preferred time')

@section('content')
    <div class="page-header">
        <div>
            <h2>Book an appointment</h2>
            <p>Select your medical concern (such as Eye Care), pick an open time slot, and we will automatically assign an available specialist.</p>
        </div>
    </div>

    <div class="stepper" id="booking-stepper">
        <div class="active" data-step-indicator="1">
            <span>1</span>
            <div><strong>Medical Concern</strong><small>Step 1</small></div>
            <i></i>
        </div>
        <div data-step-indicator="2">
            <span>2</span>
            <div><strong>Date &amp; Time</strong><small>Step 2</small></div>
            <i></i>
        </div>
        <div data-step-indicator="3">
            <span>3</span>
            <div><strong>Review &amp; Confirm</strong><small>Step 3</small></div>
        </div>
    </div>

    <section class="card booking-card">
        <form method="POST" action="{{ route('patient.appointments.store') }}" id="patient-booking-form">
            @csrf
            <input type="hidden" name="specialization" id="input-specialization" value="{{ old('specialization', $selectedCategory) }}">
            <input type="hidden" name="appointment_date" id="input-date" value="{{ old('appointment_date', $selectedDate) }}">
            <input type="hidden" name="start_time" id="input-time" value="{{ old('start_time', collect($slots)->firstWhere('disabled', false)['time'] ?? '') }}">

            {{-- Step 1: Medical Concern --}}
            <div class="booking-section" data-step-panel="1">
                <div class="section-title">
                    <span>1</span>
                    <div>
                        <h2>What is your medical concern?</h2>
                        <p>Choose a clinic category (e.g., Eye Care, General Medicine, Pediatrics). Our system will randomly assign an available doctor specializing in your concern.</p>
                    </div>
                </div>

                <div class="search" style="width:100%; margin-bottom:14px;">
                    <x-clinic-icon name="search" size="18" />
                    <input type="search" id="concern-search-input" placeholder="Search medical concerns (e.g. Eye Care, Dermatology)...">
                </div>

                <div class="doctor-picker" id="concern-picker-grid">
                    @forelse ($categories as $cat)
                        <button
                            type="button"
                            class="doctor-option concern-option {{ old('specialization', $selectedCategory) === $cat->specialization ? 'selected' : '' }}"
                            data-specialization="{{ $cat->specialization }}"
                            data-duration="{{ $cat->min_duration ?? 30 }}"
                            onclick="selectConcern(this)"
                        >
                            <div class="stat-icon teal"><x-clinic-icon name="heart" /></div>
                            <div>
                                <h3>{{ $cat->specialization }}</h3>
                                <p>{{ $cat->doctors_count }} {{ Str::plural('specialist', $cat->doctors_count) }} available</p>
                                <span><i></i>Auto-assigned doctor · {{ $cat->min_duration ?? 30 }} min</span>
                            </div>
                            <span class="radio"><i></i></span>
                        </button>
                    @empty
                        <div style="grid-column:1/-1; padding:24px; text-align:center; color:var(--muted);">
                            No active medical concerns are available at the moment.
                        </div>
                    @endforelse
                </div>

                @if ($errors->has('specialization'))
                    <div class="error-banner">
                        <x-clinic-icon name="alert" />
                        <span>
                            <strong>Please select a medical concern</strong>
                            {{ $errors->first('specialization') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Step 2: Date & Time --}}
            <div class="booking-section" data-step-panel="2" hidden>
                <div class="section-title">
                    <span>2</span>
                    <div>
                        <h2>Pick date &amp; time</h2>
                        <p>Showing slots where at least one specialist for your selected concern is available.</p>
                    </div>
                </div>

                <div class="date-strip" style="grid-template-columns: repeat(7, 1fr);">
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

                <div class="form-grid" style="margin-top:16px; align-items:end;">
                    <label class="field">
                        <span>Or choose any future date</span>
                        <input
                            type="date"
                            id="custom-date-input"
                            min="{{ now()->toDateString() }}"
                            value="{{ $selectedDate }}"
                            onchange="selectCustomDate(this.value)"
                        >
                    </label>
                </div>

                <h3 class="slot-title">
                    Available times <small id="selected-date-label">{{ collect($dates)->firstWhere('value', $selectedDate)['full'] ?? $selectedDate }}</small>
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

                @if ($errors->has('start_time') || $errors->has('appointment_date'))
                    <div class="error-banner">
                        <x-clinic-icon name="alert" />
                        <span>
                            <strong>Please check your slot selection</strong>
                            {{ $errors->first('start_time') ?: $errors->first('appointment_date') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Step 3: Review & Confirm --}}
            <div class="booking-section" data-step-panel="3" hidden>
                <div class="section-title">
                    <span><x-clinic-icon name="check" size="18" /></span>
                    <div>
                        <h2>Review &amp; confirm</h2>
                        <p>Confirm your medical concern and preferred schedule.</p>
                    </div>
                </div>

                <div class="confirm-grid">
                    <div class="confirm-person">
                        <span>Patient</span>
                        <div>
                            <div>
                                <strong>{{ $patient->full_name }}</strong>
                                <small>{{ $patient->patient_code }} · {{ $patient->phone }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="confirm-person">
                        <span>Medical Concern &amp; Doctor</span>
                        <div>
                            <div>
                                <strong id="confirm-concern-name">{{ old('specialization', $selectedCategory) ?: 'Select concern' }}</strong>
                                <small>Randomly assigned from available specialists</small>
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
                            <strong id="confirm-time-label">{{ collect($slots)->firstWhere('disabled', false)['label'] ?? 'Select time slot' }}</strong>
                        </span>
                    </div>
                </div>

                <div class="form-grid" style="margin-bottom:14px;">
                    <label class="field">
                        <span>Visit type *</span>
                        <select name="visit_type" id="input-visit-type" required>
                            @php
                                $visitTypes = array_unique(array_merge(
                                    ['Consultation', 'General Checkup', 'Follow-up'],
                                    $categories->pluck('specialization')->all()
                                ));
                            @endphp
                            @foreach ($visitTypes as $vt)
                                <option value="{{ $vt }}" @selected(old('visit_type', $selectedCategory ?: 'Consultation') === $vt)>{{ $vt }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <label class="field">
                    <span>Describe your concern / symptoms</span>
                    <textarea name="reason" placeholder="Tell us about your symptoms or what you need help with...">{{ old('reason') }}</textarea>
                </label>

                <div class="info-banner">
                    <x-clinic-icon name="bell" />
                    <span>Once submitted, an available doctor for your concern will be automatically assigned. Both you and your doctor will confirm when the visit is completed.</span>
                </div>
            </div>

            <div class="booking-actions">
                <button type="button" class="btn secondary" id="btn-back" onclick="prevStep()">Cancel</button>
                <button type="button" class="btn primary" id="btn-next" onclick="nextStep()">
                    <span>Continue</span><x-clinic-icon name="arrow" size="16" />
                </button>
                <button type="submit" class="btn primary" id="btn-submit" hidden>
                    <x-clinic-icon name="check" size="16" /><span>Request Appointment</span>
                </button>
            </div>
        </form>
    </section>
@endsection

@push('scripts')
<script>
    let currentStep = {{ $errors->hasAny(['start_time', 'appointment_date']) ? 2 : 1 }};
    const slotsEndpoint = @json(route('patient.categories.slots'));

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
        document.getElementById('btn-next').hidden = currentStep === 3;
        document.getElementById('btn-submit').hidden = currentStep !== 3;
    }

    function nextStep() {
        if (currentStep === 1 && !document.getElementById('input-specialization').value) {
            alert('Please choose a medical concern first.');
            return;
        }
        if (currentStep === 2 && !document.getElementById('input-time').value) {
            alert('Please choose an available time slot.');
            return;
        }
        if (currentStep < 3) {
            currentStep++;
            renderStep();
        }
    }

    function prevStep() {
        if (currentStep === 1) {
            window.location.href = "{{ route('patient.dashboard') }}";
            return;
        }
        currentStep--;
        renderStep();
    }

    function selectConcern(btn) {
        document.querySelectorAll('.concern-option').forEach(r => r.classList.remove('selected'));
        btn.classList.add('selected');
        const spec = btn.dataset.specialization;
        document.getElementById('input-specialization').value = spec;
        document.getElementById('confirm-concern-name').textContent = spec;

        const visitTypeSelect = document.getElementById('input-visit-type');
        if (visitTypeSelect) {
            const match = Array.from(visitTypeSelect.options).find(opt => opt.value === spec);
            if (match) {
                visitTypeSelect.value = match.value;
            }
        }

        const currentDate = document.getElementById('input-date').value;
        loadSlots(spec, currentDate);
    }

    function selectDate(btn) {
        document.querySelectorAll('.date-strip button').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        const dateVal = btn.dataset.dateValue;
        document.getElementById('input-date').value = dateVal;
        document.getElementById('custom-date-input').value = dateVal;
        const spec = document.getElementById('input-specialization').value;
        if (spec) {
            loadSlots(spec, dateVal);
        }
    }

    function selectCustomDate(dateVal) {
        if (!dateVal) return;
        document.querySelectorAll('.date-strip button').forEach(b => {
            b.classList.toggle('selected', b.dataset.dateValue === dateVal);
        });
        document.getElementById('input-date').value = dateVal;
        const spec = document.getElementById('input-specialization').value;
        if (spec) {
            loadSlots(spec, dateVal);
        }
    }

    async function loadSlots(category, dateVal) {
        const grid = document.getElementById('time-slots-grid');
        grid.innerHTML = '<div style="grid-column:1/-1; padding:12px; color:var(--muted); font-size:11px;">Loading available time slots...</div>';
        try {
            const url = `${slotsEndpoint}?category=${encodeURIComponent(category)}&date=${encodeURIComponent(dateVal)}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
            if (!res.ok) throw new Error('Failed to load slots');
            const data = await res.json();

            document.getElementById('selected-date-label').textContent = data.dateLabel;
            document.getElementById('confirm-date-label').textContent = data.dateLabel;

            grid.innerHTML = '';
            let picked = false;
            document.getElementById('input-time').value = '';
            document.getElementById('confirm-time-label').textContent = 'Select time slot';

            data.slots.forEach(slot => {
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.dataset.timeValue = slot.time;
                btn.dataset.timeLabel = slot.label;
                btn.disabled = !!slot.disabled;
                btn.innerHTML = slot.label + (slot.disabled && slot.reason ? `<small>${slot.reason}</small>` : '');

                if (!slot.disabled && !picked) {
                    picked = true;
                    btn.classList.add('selected');
                    document.getElementById('input-time').value = slot.time;
                    document.getElementById('confirm-time-label').textContent = slot.label;
                }

                btn.addEventListener('click', () => selectTimeSlot(btn));
                grid.appendChild(btn);
            });
        } catch (err) {
            grid.innerHTML = '<div style="grid-column:1/-1; padding:12px; color:#b91c1c; font-size:11px;">Could not load slots. Please try another date.</div>';
        }
    }

    function selectTimeSlot(btn) {
        document.querySelectorAll('#time-slots-grid button').forEach(b => b.classList.remove('selected'));
        btn.classList.add('selected');
        document.getElementById('input-time').value = btn.dataset.timeValue;
        document.getElementById('confirm-time-label').textContent = btn.dataset.timeLabel;
    }

    document.getElementById('concern-search-input')?.addEventListener('input', function (e) {
        const q = e.target.value.toLowerCase();
        document.querySelectorAll('.concern-option').forEach(card => {
            card.hidden = q !== '' && !card.textContent.toLowerCase().includes(q);
        });
    });

    renderStep();
</script>
@endpush
