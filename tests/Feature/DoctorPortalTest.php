<?php

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;

test('doctor can view dashboard, own appointments, schedule, and assigned patients', function () {
    $doctorUser = User::factory()->doctor()->create();
    $doctor = Doctor::factory()->for($doctorUser)->create();
    $patient = Patient::factory()->create(['full_name' => 'Assigned Patient']);

    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'appointment_date' => now()->toDateString(),
        'status' => AppointmentStatus::Confirmed,
    ]);

    $this->actingAs($doctorUser);

    $this->get(route('doctor.dashboard'))
        ->assertOk()
        ->assertSee('Assigned Patient');

    $this->get(route('doctor.appointments.index'))
        ->assertOk()
        ->assertSee($appointment->appointment_code);

    $this->get(route('doctor.appointments.show', $appointment))
        ->assertOk()
        ->assertSee('Consultation notes');

    $this->get(route('doctor.schedules.index'))->assertOk();

    $this->get(route('doctor.patients.index'))
        ->assertOk()
        ->assertSee('Assigned Patient');

    $this->get(route('doctor.patients.show', $patient))->assertOk();
});

test('doctor cannot access another doctors appointments or unrelated patients', function () {
    $doctorUserA = User::factory()->doctor()->create();
    $doctorA = Doctor::factory()->for($doctorUserA)->create();

    $doctorUserB = User::factory()->doctor()->create();
    $doctorB = Doctor::factory()->for($doctorUserB)->create();

    $otherPatient = Patient::factory()->create(['full_name' => 'Other Doctor Patient']);
    $otherAppointment = Appointment::factory()->create([
        'doctor_id' => $doctorB->id,
        'patient_id' => $otherPatient->id,
    ]);

    $this->actingAs($doctorUserA);

    $this->get(route('doctor.appointments.show', $otherAppointment))->assertNotFound();
    $this->patch(route('doctor.appointments.notes', $otherAppointment), [
        'clinical_notes' => 'Unauthorized note',
    ])->assertForbidden();
    $this->patch(route('doctor.appointments.status', $otherAppointment), [
        'status' => 'in_progress',
    ])->assertNotFound();
    $this->get(route('doctor.patients.show', $otherPatient))->assertNotFound();
});

test('doctor can start own appointment, save private clinical notes, and complete only after patient also confirms completion', function () {
    $doctorUser = User::factory()->doctor()->create();
    $doctor = Doctor::factory()->for($doctorUser)->create();
    $patientUser = User::factory()->patient()->create();
    $patient = Patient::factory()->create(['user_id' => $patientUser->id]);
    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'patient_id' => $patient->id,
        'status' => AppointmentStatus::Confirmed,
    ]);

    // Doctor starts consultation directly -> InProgress
    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.status', $appointment), [
            'status' => AppointmentStatus::InProgress->value,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::InProgress);

    // Doctor saves clinical notes
    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.notes', $appointment), [
            'clinical_notes' => 'Patient vitals normal. Prescribed rest.',
            'is_draft' => 0,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->clinical_notes)->toBe('Patient vitals normal. Prescribed rest.');
    expect($appointment->fresh()->notes_draft)->toBeFalse();

    // Doctor confirms completion -> waits for patient
    $this->actingAs($doctorUser)
        ->patch(route('doctor.appointments.status', $appointment), [
            'status' => AppointmentStatus::Completed->value,
        ])
        ->assertRedirect(route('doctor.appointments.show', $appointment));

    expect($appointment->fresh()->doctor_completed_at)->not->toBeNull();
    expect($appointment->fresh()->status)->toBe(AppointmentStatus::InProgress);

    // Patient confirms completion -> now Completed
    $this->actingAs($patientUser)
        ->patch(route('patient.appointments.status', $appointment), [
            'status' => AppointmentStatus::Completed->value,
        ])
        ->assertRedirect(route('patient.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Completed);
});

test('doctor can block and unblock own schedule slot', function () {
    $doctorUser = User::factory()->doctor()->create();
    $doctor = Doctor::factory()->for($doctorUser)->create();
    $date = now()->startOfWeek()->toDateString();

    $this->actingAs($doctorUser)
        ->post(route('doctor.schedules.store'), [
            'schedule_date' => $date,
            'start_time' => '14:00:00',
            'status' => 'blocked',
            'note' => 'Hospital rounds',
        ])
        ->assertRedirect(route('doctor.schedules.index', ['week' => $date]));

    $schedule = $doctor->schedules()->where('start_time', '14:00:00')->first();
    expect($schedule)->not->toBeNull();
    expect($schedule->status)->toBe('blocked');

    $this->actingAs($doctorUser)
        ->delete(route('doctor.schedules.destroy', $schedule))
        ->assertRedirect(route('doctor.schedules.index', ['week' => $date]));

    expect($doctor->schedules()->where('start_time', '14:00:00')->exists())->toBeFalse();
});
