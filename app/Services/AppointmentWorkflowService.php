<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AppointmentWorkflowService
{
    /**
     * Record a doctor or patient confirmation that an in-progress appointment is completed.
     * Transitions the appointment to Completed only once both doctor and patient have confirmed.
     *
     * @return array{appointment: Appointment, transitioned: bool, message: string}
     *
     * @throws ValidationException
     */
    public function confirmCompletion(Appointment $appointment, User $actor): array
    {
        if (! $actor->isDoctor() && ! $actor->isPatient()) {
            throw ValidationException::withMessages([
                'status' => 'Appointment completion must be confirmed by both the doctor and the patient.',
            ]);
        }

        if (! $appointment->status->canTransitionTo(AppointmentStatus::Completed)) {
            throw ValidationException::withMessages([
                'status' => "Cannot confirm completion while appointment is {$appointment->status->label()}.",
            ]);
        }

        $actorColumn = $actor->isDoctor() ? 'doctor_completed_at' : 'patient_completed_at';
        $otherColumn = $actor->isDoctor() ? 'patient_completed_at' : 'doctor_completed_at';
        $actorLabel = $actor->isDoctor() ? 'Doctor' : 'Patient';
        $otherLabel = $actor->isDoctor() ? 'patient' : 'doctor';

        $updates = [];
        if ($appointment->{$actorColumn} === null) {
            $updates[$actorColumn] = now();
        }

        $otherConfirmed = $appointment->{$otherColumn} !== null;

        if ($otherConfirmed) {
            $updates['status'] = AppointmentStatus::Completed;
            $updates['completed_at'] = now();
        }

        if ($updates !== []) {
            $appointment->update($updates);
        }

        if ($otherConfirmed) {
            AuditLog::record(
                $actor,
                'Updated appointment',
                "Appointment #{$appointment->appointment_code}",
                'In Progress → Completed (confirmed by both doctor and patient)'
            );

            return [
                'appointment' => $appointment,
                'transitioned' => true,
                'message' => 'Both doctor and patient confirmed completion. Appointment is now Completed.',
            ];
        }

        AuditLog::record(
            $actor,
            "{$actorLabel} confirmed appointment completion",
            "Appointment #{$appointment->appointment_code}",
            "Awaiting {$otherLabel} confirmation"
        );

        return [
            'appointment' => $appointment,
            'transitioned' => false,
            'message' => "Completion confirmed. Waiting for {$otherLabel} to confirm completion.",
        ];
    }

    /**
     * Transition an appointment to a valid next status and record an audit log entry.
     *
     * @throws ValidationException
     */
    public function transitionStatus(Appointment $appointment, AppointmentStatus $nextStatus, User $actor): Appointment
    {
        $currentStatus = $appointment->status;

        if (! $currentStatus->canTransitionTo($nextStatus)) {
            throw ValidationException::withMessages([
                'status' => "Cannot transition appointment from {$currentStatus->label()} to {$nextStatus->label()}.",
            ]);
        }

        $timestamps = match ($nextStatus) {
            AppointmentStatus::Confirmed => ['confirmed_at' => now()],
            AppointmentStatus::InProgress => ['started_at' => now()],
            AppointmentStatus::Completed => ['completed_at' => now()],
            AppointmentStatus::Cancelled => ['cancelled_at' => now()],
            default => [],
        };

        $appointment->update(array_merge([
            'status' => $nextStatus,
        ], $timestamps));

        AuditLog::record(
            $actor,
            'Updated appointment',
            "Appointment #{$appointment->appointment_code}",
            "{$currentStatus->label()} → {$nextStatus->label()}"
        );

        return $appointment;
    }

    public function cancel(Appointment $appointment, string $reason, ?string $notes, User $actor): Appointment
    {
        $currentStatus = $appointment->status;

        if (! $currentStatus->canTransitionTo(AppointmentStatus::Cancelled)) {
            throw ValidationException::withMessages([
                'status' => "Cannot cancel an appointment with status {$currentStatus->label()}.",
            ]);
        }

        $appointment->update([
            'status' => AppointmentStatus::Cancelled,
            'cancellation_reason' => $reason,
            'cancellation_notes' => $notes,
            'cancelled_at' => now(),
        ]);

        AuditLog::record(
            $actor,
            'Cancelled appointment',
            "Appointment #{$appointment->appointment_code}",
            "{$currentStatus->label()} → Cancelled ({$reason})"
        );

        return $appointment;
    }
}
