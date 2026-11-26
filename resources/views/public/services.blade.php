@extends('layouts.public')

@section('title', 'Clinic Services & Categories · ClinicCare')

@section('content')
<main>
    <section class="pub-page-hero">
        <div class="pub-container">
            <span class="eyebrow">CLINIC SERVICES &amp; CATEGORIES</span>
            <h1>Care made clear and simple</h1>
            <p>Browse our medical specialties and appointment lengths—then pick your category and doctor to book online.</p>
        </div>
    </section>

    <section class="pub-section" style="padding-top:44px;">
        <div class="pub-container">
            <div class="pub-grid-3" style="margin-top:0;">
                @foreach ($categories as $index => $cat)
                    @php
                        $iconName = match (strtolower($cat->specialization)) {
                            'eye care' => 'activity',
                            'pediatrics' => 'heart',
                            'dermatology' => 'shield',
                            default => 'doctor',
                        };
                    @endphp
                    <article class="pub-service-card {{ $index === 0 ? 'featured' : '' }}">
                        <div class="pub-service-icon">
                            <x-clinic-icon :name="$iconName" size="22" />
                        </div>
                        <h3>{{ $cat->specialization }}</h3>
                        <p>Comprehensive {{ strtolower($cat->specialization) }} consultations, assessments, and ongoing care plans.</p>
                        <div class="pub-service-foot">
                            <span style="color:var(--muted); display:inline-flex; align-items:center; gap:6px;">
                                <x-clinic-icon name="clock" size="15" />{{ $cat->min_duration ?? 30 }} min · {{ $cat->doctors_count }} {{ Str::plural('doctor', $cat->doctors_count) }}
                            </span>
                            <a href="{{ route('patient.appointments.create', ['category' => $cat->specialization]) }}" class="text-btn">
                                Book <x-clinic-icon name="arrow" size="14" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:40px; border-radius:20px; background:#0f172a; padding:32px; color:white; display:flex; align-items:center; justify-content:space-between; gap:20px; flex-wrap:wrap;">
                <div>
                    <h3 style="font-size:20px; font-weight:700;">Not sure which category you need?</h3>
                    <p style="margin-top:6px; font-size:14px; color:#cbd5e1;">Start with General Medicine or browse all available doctors when booking.</p>
                </div>
                <a href="{{ route('patient.appointments.create') }}" class="btn primary">Start booking</a>
            </div>
        </div>
    </section>
</main>
@endsection
