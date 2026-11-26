<?php

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;

test('any visitor can register a patient account and reach the patient dashboard', function () {
    $this->get(route('patient.register'))->assertOk();

    $response = $this->post(route('patient.register.store'), [
        'name' => 'Alex Rivera',
        'email' => 'alex.rivera@example.com',
        'phone' => '(555) 987-6543',
        'date_of_birth' => '1994-07-12',
        'sex' => 'Male',
        'address' => '210 Pine Street',
        'password' => 'SecretPass123!',
        'password_confirmation' => 'SecretPass123!',
    ]);

    $response->assertRedirect(route('patient.dashboard'));

    $user = User::where('email', 'alex.rivera@example.com')->first();
    expect($user)->not->toBeNull();
    expect($user->role)->toBe(UserRole::Patient);
    expect($user->patient)->not->toBeNull();
    expect($user->patient->full_name)->toBe('Alex Rivera');

    $this->assertAuthenticatedAs($user);
});

test('patient login and role middleware isolate patient portal from admin and doctor portals', function () {
    $patientUser = User::factory()->patient()->create();
    Patient::factory()->create(['user_id' => $patientUser->id]);

    $admin = User::factory()->admin()->create();
    $doctorUser = User::factory()->doctor()->create();
    Doctor::factory()->for($doctorUser)->create();

    $this->get(route('patient.dashboard'))->assertRedirect(route('patient.login'));

    $this->post(route('admin.login.store'), [
        'email' => $patientUser->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');
    $this->assertGuest();

    $this->post(route('doctor.login.store'), [
        'email' => $patientUser->email,
        'password' => 'password',
    ])->assertSessionHasErrors('email');
    $this->assertGuest();

    $this->post(route('patient.login.store'), [
        'email' => $patientUser->email,
        'password' => 'password',
    ])->assertRedirect(route('patient.dashboard', absolute: false));

    $this->actingAs($patientUser)->get(route('admin.dashboard'))->assertForbidden();
    $this->actingAs($patientUser)->get(route('doctor.dashboard'))->assertForbidden();

    $this->actingAs($admin)->get(route('patient.dashboard'))->assertForbidden();
    $this->actingAs($doctorUser)->get(route('patient.dashboard'))->assertForbidden();
});

test('patient chooses a medical concern and system assigns a random available doctor for that concern', function () {
    $patientUser = User::factory()->patient()->create();
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);
    $otherPatient = Patient::factory()->create();

    $busyEyeDoctor = Doctor::factory()->create([
        'full_name' => 'Dr. Busy Eye',
        'specialization' => 'Eye Care',
        'is_active' => true,
    ]);
    $freeEyeDoctor = Doctor::factory()->create([
        'full_name' => 'Dr. Sofia Alvarez',
        'specialization' => 'Eye Care',
        'is_active' => true,
    ]);
    $inactiveEyeDoctor = Doctor::factory()->inactive()->create([
        'specialization' => 'Eye Care',
    ]);
    $generalDoctor = Doctor::factory()->create([
        'full_name' => 'Dr. Elena Morris',
        'specialization' => 'General Medicine',
        'is_active' => true,
    ]);

    $date = now()->addDays(2)->toDateString();

    Appointment::factory()->create([
        'doctor_id' => $busyEyeDoctor->id,
        'patient_id' => $otherPatient->id,
        'appointment_date' => $date,
        'start_time' => '09:00:00',
        'status' => AppointmentStatus::Confirmed,
    ]);

    $this->actingAs($patientUser)
        ->get(route('patient.appointments.create', ['category' => 'Eye Care']))
        ->assertOk()
        ->assertSee('Eye Care')
        ->assertSee('Auto-assigned doctor');

    $this->actingAs($patientUser)
        ->getJson(route('patient.categories.slots', ['category' => 'Eye Care', 'date' => $date]))
        ->assertOk()
        ->assertJsonStructure(['date', 'dateLabel', 'slots']);

    $storeResponse = $this->actingAs($patientUser)->post(route('patient.appointments.store'), [
        'specialization' => 'Eye Care',
        'appointment_date' => $date,
        'start_time' => '09:00:00',
        'visit_type' => 'Eye Care',
        'reason' => 'Annual vision screening and blurry distance sight',
    ]);

    $appointment = Appointment::where('patient_id', $patient->id)->first();

    expect($appointment)->not->toBeNull();
    expect($appointment->doctor_id)->toBe($freeEyeDoctor->id);
    expect($appointment->doctor_id)->not->toBe($inactiveEyeDoctor->id);
    expect($appointment->doctor_id)->not->toBe($generalDoctor->id);
    expect($appointment->status)->toBe(AppointmentStatus::Pending);
    $storeResponse->assertRedirect(route('patient.appointments.show', $appointment));
});

test('patient booking rejects slots when all doctors for the concern are booked or blocked, and rejects past dates', function () {
    $patientUser = User::factory()->patient()->create();
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);
    $otherPatient = Patient::factory()->create();
    $doctor = Doctor::factory()->create(['specialization' => 'Eye Care', 'is_active' => true]);
    $date = now()->addDay()->toDateString();

    DoctorSchedule::factory()->create([
        'doctor_id' => $doctor->id,
        'schedule_date' => $date,
        'start_time' => '12:00:00',
        'end_time' => '13:00:00',
        'status' => 'blocked',
    ]);

    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $otherPatient->id,
        'appointment_date' => $date,
        'start_time' => '10:00:00',
        'status' => AppointmentStatus::Confirmed,
    ]);

    $this->actingAs($patientUser)->post(route('patient.appointments.store'), [
        'specialization' => 'Eye Care',
        'appointment_date' => $date,
        'start_time' => '10:00:00',
        'visit_type' => 'Eye Care',
    ])->assertSessionHasErrors('start_time');

    $this->actingAs($patientUser)->post(route('patient.appointments.store'), [
        'specialization' => 'Eye Care',
        'appointment_date' => $date,
        'start_time' => '12:00:00',
        'visit_type' => 'Eye Care',
    ])->assertSessionHasErrors('start_time');

    $this->actingAs($patientUser)->post(route('patient.appointments.store'), [
        'specialization' => 'Eye Care',
        'appointment_date' => now()->subDay()->toDateString(),
        'start_time' => '09:00:00',
        'visit_type' => 'Eye Care',
    ])->assertSessionHasErrors('appointment_date');

    expect(Appointment::where('patient_id', $patient->id)->count())->toBe(0);
});

test('patient can view and cancel own appointment, update profile, and cannot view other patients visits or clinical notes', function () {
    $patientUser = User::factory()->patient()->create();
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    $otherPatient = Patient::factory()->create();
    $otherAppointment = Appointment::factory()->create(['patient_id' => $otherPatient->id]);

    $ownAppointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
        'status' => AppointmentStatus::Pending,
        'clinical_notes' => 'Private doctor-only note that must stay hidden',
    ]);

    $this->actingAs($patientUser)
        ->get(route('patient.appointments.show', $otherAppointment))
        ->assertNotFound();

    $this->actingAs($patientUser)
        ->get(route('patient.appointments.show', $ownAppointment))
        ->assertOk()
        ->assertDontSee('Private doctor-only note that must stay hidden');

    $this->actingAs($patientUser)
        ->patch(route('patient.appointments.cancel', $ownAppointment), [
            'cancellation_reason' => 'Patient requested',
            'cancellation_notes' => 'Rescheduling for next week',
        ])
        ->assertRedirect(route('patient.appointments.show', $ownAppointment));

    expect($ownAppointment->fresh()->status)->toBe(AppointmentStatus::Cancelled);

    $this->actingAs($patientUser)
        ->patch(route('patient.profile.update'), [
            'full_name' => 'Updated Patient Name',
            'phone' => '(555) 111-2222',
            'sex' => 'Female',
            'allergy_note' => 'Dust',
        ])
        ->assertRedirect(route('patient.profile.edit'));

    expect($patient->fresh()->full_name)->toBe('Updated Patient Name');
    expect($patientUser->fresh()->name)->toBe('Updated Patient Name');
});

test('public home, doctors, and services pages render live doctors and categories', function () {
    Doctor::factory()->create([
        'full_name' => 'Dr. Sofia Alvarez',
        'specialization' => 'Eye Care',
        'is_active' => true,
    ]);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Your health. Your time.')
        ->assertSee('Eye Care')
        ->assertSee('Dr. Sofia Alvarez');

    $this->get(route('public.doctors', ['category' => 'Eye Care']))
        ->assertOk()
        ->assertSee('Dr. Sofia Alvarez');

    $this->get(route('public.services'))
        ->assertOk()
        ->assertSee('Eye Care');
});

test('patient and doctor only dual-confirm when completing an in-progress appointment', function () {
    $doctorUser = User::factory()->doctor()->create();
    $doctor = Doctor::factory()->for($doctorUser)->create();
    $patientUser = User::factory()->patient()->create();
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);

    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'status' => AppointmentStatus::Pending,
        'confirmed_at' => null,
    ]);

    // Patient cannot confirm booking or start — only completion
    $this->actingAs($patientUser)
        ->patch(route('patient.appointments.status', $appointment), [
            'status' => AppointmentStatus::Confirmed->value,
        ])
        ->assertSessionHasErrors('status');

    // Doctor confirms booking and starts consultation directly
    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.status', $appointment), [
            'status' => AppointmentStatus::Confirmed->value,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Confirmed);

    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.status', $appointment), [
            'status' => AppointmentStatus::InProgress->value,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::InProgress);

    // Patient confirms completion first -> stays InProgress until Doctor also confirms
    $this->actingAs($patientUser)
        ->patch(route('patient.appointments.status', $appointment), [
            'status' => AppointmentStatus::Completed->value,
        ])
        ->assertRedirect(route('patient.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::InProgress);
    expect($appointment->fresh()->patient_completed_at)->not->toBeNull();

    // Doctor confirms completion -> now Completed
    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.status', $appointment), [
            'status' => AppointmentStatus::Completed->value,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Completed);
    expect($appointment->fresh()->completed_at)->not->toBeNull();
});
