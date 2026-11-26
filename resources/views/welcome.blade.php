@extends('layouts.public')

@section('title', 'ClinicCare · Your Health, Your Time, Your Convenience')

@section('content')
<main>
    {{-- Hero Section --}}
    <section class="pub-hero">
        <div class="pub-hero-orb"></div>
        <div class="pub-container pub-hero-grid">
            <div class="pub-hero-copy">
                <div class="pub-pill">
                    <i></i>
                    <span>ACCEPTING NEW PATIENTS</span>
                </div>
                <h1>Your health. Your time. <span>Your convenience.</span></h1>
                <p>Quality healthcare should fit your life. Register as a patient, pick any category—from Eye Care to General Medicine—and book trusted clinicians online in minutes.</p>

                <div class="pub-hero-btns">
                    <a href="{{ route('patient.appointments.create') }}" class="btn primary">
                        <x-clinic-icon name="calendar" size="18" />
                        <span>Book an appointment</span>
                        <x-clinic-icon name="arrow" size="17" />
                    </a>
                    <a href="{{ route('public.doctors') }}" class="btn secondary">
                        <span>Meet our doctors</span>
                    </a>
                </div>

                <div class="pub-checks">
                    <span><i><x-clinic-icon name="check" size="12" /></i>Same-week visits</span>
                    <span><i><x-clinic-icon name="check" size="12" /></i>Secure booking</span>
                    <span><i><x-clinic-icon name="check" size="12" /></i>No phone calls</span>
                </div>
            </div>

            <div class="pub-hero-media">
                <div class="pub-photo-frame">
                    <img src="{{ asset('images/clinic-team.png') }}" alt="ClinicCare medical team">
                </div>
                <div class="pub-float-card">
                    <div class="pub-float-icon"><x-clinic-icon name="clock" size="20" /></div>
                    <div>
                        <small>Next available</small>
                        <strong>Today · Open Slots</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Strip --}}
    <section class="pub-stats">
        <div class="pub-container pub-stats-grid">
            <div class="pub-stat-item">
                <strong>15+</strong>
                <span>Years of care</span>
            </div>
            <div class="pub-stat-item">
                <strong>{{ number_format(max(12000, $patientsCount)) }}+</strong>
                <span>Patients served</span>
            </div>
            <div class="pub-stat-item">
                <strong>{{ $activeDoctorsCount }}</strong>
                <span>Expert doctors</span>
            </div>
            <div class="pub-stat-item">
                <strong>4.9/5</strong>
                <span>Patient rating</span>
            </div>
        </div>
    </section>

    {{-- Categories & Services Section --}}
    <section class="pub-section">
        <div class="pub-container">
            <div class="pub-section-head">
                <div>
                    <span class="eyebrow">OUR CATEGORIES &amp; SERVICES</span>
                    <h2>Thoughtful care for every stage of life</h2>
                    <p>Pick any specialty category—like Eye Care, General Medicine, Pediatrics, or Dermatology—and reserve an open slot with your chosen doctor.</p>
                </div>
                <a href="{{ route('public.services') }}" class="text-btn">
                    <span>View all categories</span>
                    <x-clinic-icon name="arrow" size="16" />
                </a>
            </div>

            <div class="pub-grid-3">
                @foreach ($categories->take(6) as $index => $cat)
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
                        <p>Dedicated {{ strtolower($cat->specialization) }} consultations, screenings, and personalized follow-up care with our board-certified team.</p>
                        <div class="pub-service-foot">
                            <strong>{{ $cat->doctors_count }} {{ Str::plural('Doctor', $cat->doctors_count) }}</strong>
                            <a href="{{ route('patient.appointments.create', ['category' => $cat->specialization]) }}" class="text-btn">
                                Book {{ $cat->specialization }} <x-clinic-icon name="arrow" size="14" />
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Meet Your Care Team --}}
    <section class="pub-section tinted">
        <div class="pub-container">
            <div class="pub-section-head">
                <div>
                    <span class="eyebrow">MEET YOUR CARE TEAM</span>
                    <h2>Experienced doctors who listen</h2>
                    <p>Compassionate clinicians committed to clear answers and personalized care across every specialty.</p>
                </div>
                <a href="{{ route('public.doctors') }}" class="text-btn">
                    <span>View all doctors</span>
                    <x-clinic-icon name="arrow" size="16" />
                </a>
            </div>

            <div class="pub-grid-3">
                @foreach ($doctors->take(3) as $doc)
                    <article class="pub-doctor-card">
                        <div class="pub-doctor-avatar avatar {{ $doc->avatar_tone }}">
                            {{ $doc->initials() }}
                        </div>
                        <h3>{{ $doc->full_name }}</h3>
                        <p>{{ $doc->specialization }}</p>
                        <div class="pub-doctor-foot">
                            <span class="pub-avail"><i></i>{{ $doc->availability_summary ?: 'Available' }}</span>
                            <a href="{{ route('patient.appointments.create', ['category' => $doc->specialization]) }}" class="text-btn">Book now</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- How It Works --}}
    <section class="pub-section">
        <div class="pub-container">
            <div class="pub-section-head">
                <div>
                    <span class="eyebrow">HOW IT WORKS</span>
                    <h2>Care is only a few clicks away</h2>
                    <p>A simple self-service booking process designed to save you time.</p>
                </div>
            </div>

            <div class="pub-steps">
                <div>
                    <div class="pub-step-num">1</div>
                    <h3>Register &amp; choose a category</h3>
                    <p>Create your patient account and pick any category—such as Eye Care or General Medicine—and your preferred doctor.</p>
                </div>
                <div>
                    <div class="pub-step-num">2</div>
                    <h3>Pick an available time</h3>
                    <p>See real-time open slots for your doctor and choose a date and time that fits your schedule.</p>
                </div>
                <div>
                    <div class="pub-step-num">3</div>
                    <h3>Visit the clinic</h3>
                    <p>Track your appointment status from your Patient Portal and arrive at your scheduled time.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Ready When You Are CTA --}}
    <div class="pub-cta-wrap">
        <section class="pub-cta">
            <div>
                <span>READY WHEN YOU ARE</span>
                <h2>Take the next step for your health.</h2>
                <p>Appointments are available this week across all clinic categories.</p>
            </div>
            <a href="{{ auth()->check() && auth()->user()->isPatient() ? route('patient.appointments.create') : route('patient.register') }}" class="btn">
                <span>{{ auth()->check() ? 'Find an appointment' : 'Create account & book' }}</span>
                <x-clinic-icon name="arrow" size="17" />
            </a>
        </section>
    </div>
</main>
@endsection
