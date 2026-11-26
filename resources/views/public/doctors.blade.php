@extends('layouts.public')

@section('title', 'Our Doctors · ClinicCare')

@section('content')
<main>
    <section class="pub-page-hero">
        <div class="pub-container">
            <span class="eyebrow">OUR DOCTORS</span>
            <h1>Trusted experts, personal care</h1>
            <p>Meet our dedicated team of board-certified clinicians. Filter by specialty category—such as Eye Care, General Medicine, Pediatrics, or Dermatology—and book directly.</p>
        </div>
    </section>

    <section class="pub-section" style="padding-top:44px;">
        <div class="pub-container">
            <form method="GET" action="{{ route('public.doctors') }}" style="display:flex; gap:12px; flex-wrap:wrap; margin-bottom:28px;">
                <div class="search" style="flex:1; min-width:240px; height:44px; border-radius:12px;">
                    <x-clinic-icon name="search" size="18" />
                    <input type="search" name="search" value="{{ $search }}" placeholder="Search doctors or specialties...">
                </div>
                <select name="category" onchange="this.form.submit()" style="height:44px; padding:0 16px; border:1px solid #e2e8f0; border-radius:12px; background:white; font-size:13px; color:#334155;">
                    <option value="all">All specialties</option>
                    @foreach ($categories as $cat)
                        <option value="{{ $cat }}" @selected($selectedCategory === $cat)>{{ $cat }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn secondary" style="height:44px;">Filter</button>
            </form>

            <div class="pub-grid-3" style="margin-top:0;">
                @forelse ($doctors as $doc)
                    <article class="pub-doctor-card">
                        <div class="pub-doctor-avatar avatar {{ $doc->avatar_tone }}">
                            {{ $doc->initials() }}
                        </div>
                        <h3>{{ $doc->full_name }}</h3>
                        <p>{{ $doc->specialization }} · {{ $doc->consultation_duration }} min</p>
                        <div class="pub-doctor-foot">
                            <span class="pub-avail"><i></i>{{ $doc->availability_summary ?: 'Available' }}</span>
                            <a href="{{ route('patient.appointments.create', ['category' => $doc->specialization]) }}" class="text-btn">Book now</a>
                        </div>
                    </article>
                @empty
                    <div class="card" style="grid-column:1/-1; padding:36px; text-align:center; color:var(--muted);">
                        No active doctors match your filter. <a href="{{ route('public.doctors') }}" class="text-btn">Reset filters</a>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</main>
@endsection
