<?php

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;

test('admin can view dashboard and management screens', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin);

    $this->get(route('admin.dashboard'))->assertOk();
    $this->get(route('admin.appointments.index'))->assertOk();
    $this->get(route('admin.appointments.create'))->assertOk();
    $this->get(route('admin.schedules.index'))->assertOk();
    $this->get(route('admin.patients.index'))->assertOk();
    $this->get(route('admin.doctors.index'))->assertOk();
    $this->get(route('admin.reports.index'))->assertOk();
    $this->get(route('admin.users.index'))->assertOk();
    $this->get(route('admin.audit.index'))->assertOk();
    $this->get(route('admin.style-guide'))->assertOk();
});

test('admin can create and update a patient', function () {
    $admin = User::factory()->admin()->create();

    $storeResponse = $this->actingAs($admin)->post(route('admin.patients.store'), [
        'full_name' => 'Maya Anderson',
        'date_of_birth' => '1992-05-14',
        'sex' => 'Female',
        'phone' => '(555) 444-1212',
        'email' => 'maya@example.com',
        'allergy_note' => 'Sulfa drugs',
    ]);

    $patient = Patient::where('full_name', 'Maya Anderson')->first();
    expect($patient)->not->toBeNull();
    $storeResponse->assertRedirect(route('admin.patients.index'));

    $updateResponse = $this->actingAs($admin)->put(route('admin.patients.update', $patient), [
        'full_name' => 'Maya Anderson-Lee',
        'date_of_birth' => '1992-05-14',
        'sex' => 'Female',
        'phone' => '(555) 444-9999',
        'email' => 'maya@example.com',
        'allergy_note' => 'Sulfa drugs',
        'is_active' => 1,
    ]);

    $updateResponse->assertRedirect(route('admin.patients.show', $patient));
    expect($patient->fresh()->full_name)->toBe('Maya Anderson-Lee');
    expect(AuditLog::where('action', 'Created patient')->exists())->toBeTrue();
});

test('admin can create a doctor with linked doctor portal user and toggle status', function () {
    $admin = User::factory()->admin()->create();

    $response = $this->actingAs($admin)->post(route('admin.doctors.store'), [
        'full_name' => 'Dr. Hannah Brooks',
        'specialization' => 'Cardiology',
        'license_number' => 'MD-778899',
        'phone' => '(555) 890-1234',
        'email' => 'hannah@cliniccare.com',
        'consultation_duration' => 30,
    ]);

    $response->assertRedirect(route('admin.doctors.index'));

    $doctor = Doctor::where('license_number', 'MD-778899')->first();
    expect($doctor)->not->toBeNull();
    expect($doctor->user)->not->toBeNull();
    expect($doctor->user->role)->toBe(UserRole::Doctor);

    $this->actingAs($admin)
        ->patch(route('admin.doctors.toggle', $doctor))
        ->assertRedirect(route('admin.doctors.index'));

    expect($doctor->fresh()->is_active)->toBeFalse();
    expect($doctor->user->fresh()->is_active)->toBeFalse();
});

test('admin booking prevents duplicate slot conflicts and blocked schedule slots', function () {
    $admin = User::factory()->admin()->create();
    $doctor = Doctor::factory()->create(['is_active' => true]);
    $patient = Patient::factory()->create();
    $date = now()->addDay()->toDateString();

    DoctorSchedule::factory()->create([
        'doctor_id' => $doctor->id,
        'schedule_date' => $date,
        'start_time' => '11:00:00',
        'end_time' => '11:30:00',
        'status' => 'blocked',
    ]);

    $firstResponse = $this->actingAs($admin)->post(route('admin.appointments.store'), [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_date' => $date,
        'start_time' => '09:00:00',
        'visit_type' => 'General Checkup',
        'reason' => 'Annual exam',
    ]);

    $appointment = Appointment::where('doctor_id', $doctor->id)->where('start_time', '09:00:00')->first();
    expect($appointment)->not->toBeNull();
    $firstResponse->assertRedirect(route('admin.appointments.show', $appointment));

    $conflictResponse = $this->actingAs($admin)->post(route('admin.appointments.store'), [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_date' => $date,
        'start_time' => '09:00:00',
        'visit_type' => 'Consultation',
    ]);
    $conflictResponse->assertSessionHasErrors('start_time');

    $blockedResponse = $this->actingAs($admin)->post(route('admin.appointments.store'), [
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'appointment_date' => $date,
        'start_time' => '11:00:00',
        'visit_type' => 'Consultation',
    ]);
    $blockedResponse->assertSessionHasErrors('start_time');
});

test('admin appointment view hides private clinical notes and supports cancellation', function () {
    $admin = User::factory()->admin()->create();
    $appointment = Appointment::factory()->create([
        'status' => AppointmentStatus::Confirmed,
        'clinical_notes' => 'CONFIDENTIAL_CLINICAL_NOTE_TEXT',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.appointments.show', $appointment))
        ->assertOk()
        ->assertDontSee('CONFIDENTIAL_CLINICAL_NOTE_TEXT')
        ->assertDontSee('Start consultation')
        ->assertDontSee('Mark complete');

    $this->actingAs($admin)
        ->patch(route('admin.appointments.status', $appointment), [
            'status' => AppointmentStatus::InProgress->value,
        ])
        ->assertForbidden();

    $this->actingAs($admin)
        ->patch(route('admin.appointments.cancel', $appointment), [
            'cancellation_reason' => 'Patient requested',
            'cancellation_notes' => 'Rescheduling next week',
        ])
        ->assertRedirect(route('admin.appointments.show', $appointment));

    expect($appointment->fresh()->status)->toBe(AppointmentStatus::Cancelled);
});
