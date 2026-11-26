@extends('layouts.patient')

@section('title', 'My Profile')
@section('topbar-title', 'My Profile')
@section('topbar-subtitle', 'Patient ID: '.$patient->patient_code)

@section('content')
    <div class="page-header">
        <div>
            <h2>My Profile</h2>
            <p>Keep your contact details and medical allergy information up to date for clinic visits.</p>
        </div>
    </div>

    <section class="card" style="max-width: 760px; padding: 24px;">
        <form method="POST" action="{{ route('patient.profile.update') }}">
            @csrf
            @method('PATCH')

            <div class="form-grid">
                <label class="field">
                    <span>Full name *</span>
                    <input type="text" name="full_name" value="{{ old('full_name', $patient->full_name) }}" required>
                </label>

                <label class="field">
                    <span>Email address</span>
                    <input type="email" value="{{ $user->email }}" disabled>
                </label>

                <label class="field">
                    <span>Phone number *</span>
                    <input type="text" name="phone" value="{{ old('phone', $patient->phone) }}" required>
                </label>

                <label class="field">
                    <span>Date of birth</span>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $patient->date_of_birth?->toDateString()) }}">
                </label>

                <label class="field">
                    <span>Sex *</span>
                    <select name="sex" required>
                        <option value="Female" @selected(old('sex', $patient->sex) === 'Female')>Female</option>
                        <option value="Male" @selected(old('sex', $patient->sex) === 'Male')>Male</option>
                        <option value="Prefer not to say" @selected(old('sex', $patient->sex) === 'Prefer not to say')>Prefer not to say</option>
                    </select>
                </label>

                <label class="field">
                    <span>Emergency contact</span>
                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact', $patient->emergency_contact) }}" placeholder="Name · Phone number">
                </label>

                <label class="field full">
                    <span>Home address</span>
                    <input type="text" name="address" value="{{ old('address', $patient->address) }}" placeholder="Street, city">
                </label>

                <label class="field full">
                    <span>Known allergies (optional)</span>
                    <input type="text" name="allergy_note" value="{{ old('allergy_note', $patient->allergy_note) }}" placeholder="e.g. Penicillin allergy">
                </label>
            </div>

            <div class="button-row" style="margin-top:20px; justify-content:flex-end;">
                <button type="submit" class="btn primary">Save Profile</button>
            </div>
        </form>
    </section>
@endsection
