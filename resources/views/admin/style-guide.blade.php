@extends('layouts.admin')

@section('title', 'Style Guide')
@section('topbar-title', 'Style Guide')
@section('topbar-subtitle', 'ClinicCare UI Kit')

@section('content')
    <div class="page-header">
        <div>
            <h2>ClinicCare UI Kit</h2>
            <p>A practical foundation for accessible, consistent clinical workflows.</p>
        </div>
    </div>

    <div class="style-grid">
        <section class="card">
            <span class="eyebrow">COLOR</span>
            <h3>Core palette</h3>
            <div class="swatches">
                @foreach ([['#087F8C', 'Primary'], ['#0B5E75', 'Deep teal'], ['#EFF8F8', 'Soft teal'], ['#17242D', 'Ink'], ['#647680', 'Slate'], ['#F3F6F7', 'Canvas']] as [$hex, $name])
                    <div>
                        <i style="background:{{ $hex }}"></i>
                        <span>
                            <strong>{{ $name }}</strong>
                            <small>{{ $hex }}</small>
                        </span>
                    </div>
                @endforeach
            </div>
        </section>

        <section class="card">
            <span class="eyebrow">TYPOGRAPHY</span>
            <h3>Inter</h3>
            <div class="type-samples">
                <h1>Heading 1 · 30px</h1>
                <h2>Heading 2 · 24px</h2>
                <h3>Heading 3 · 17px</h3>
                <p>Body text · 14px regular</p>
                <small>CAPTION · 12PX MEDIUM</small>
            </div>
        </section>

        <section class="card span-two">
            <span class="eyebrow">COMPONENTS</span>
            <h3>Buttons &amp; status</h3>
            <div class="component-demo">
                <button type="button" class="btn primary">Primary action</button>
                <button type="button" class="btn secondary">Secondary</button>
                <button type="button" class="btn danger">Danger</button>
                @foreach (\App\Enums\AppointmentStatus::cases() as $status)
                    <x-clinic-badge :status="$status" />
                @endforeach
            </div>
        </section>

        <section class="card span-two">
            <span class="eyebrow">FORM CONTROLS</span>
            <h3>Inputs &amp; states</h3>
            <div class="component-demo form-demo">
                <label class="field">
                    <span>Default input</span>
                    <input type="text" placeholder="Enter value">
                </label>
                <label class="field has-error">
                    <span>Error input</span>
                    <input type="text" value="Invalid value">
                    <small><x-clinic-icon name="alert" size="14" />Please enter a valid value</small>
                </label>
                <label class="field">
                    <span>Select</span>
                    <select><option>Choose an option</option></select>
                </label>
                <div>
                    <span class="demo-label">Time slots</span>
                    <div class="time-slots small">
                        <button type="button">9:00 AM</button>
                        <button type="button" class="selected">9:30 AM</button>
                        <button type="button" disabled>10:00 AM</button>
                    </div>
                </div>
            </div>
        </section>

        <section class="card span-two">
            <span class="eyebrow">SYSTEM STATES</span>
            <h3>Feedback &amp; loading</h3>
            <div class="state-demo">
                <div class="toast">
                    <div class="toast-icon"><x-clinic-icon name="check" size="16" /></div>
                    <span><strong>Appointment confirmed</strong><small>Patient notification was sent.</small></span>
                </div>
                <div class="skeleton-box"><i></i><span></span><span></span></div>
                <div class="empty-state">
                    <x-clinic-icon name="calendar" />
                    <span><strong>No appointments found</strong><small>Try adjusting your filters.</small></span>
                </div>
            </div>
        </section>
    </div>
@endsection
