<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

class WeeklyScheduleBuilder
{
    /**
     * @return array<string, string>
     */
    public function hourlySlots(): array
    {
        return [
            '08:00:00' => '8:00 AM',
            '09:00:00' => '9:00 AM',
            '10:00:00' => '10:00 AM',
            '11:00:00' => '11:00 AM',
            '12:00:00' => '12:00 PM',
            '13:00:00' => '1:00 PM',
            '14:00:00' => '2:00 PM',
            '15:00:00' => '3:00 PM',
            '16:00:00' => '4:00 PM',
        ];
    }

    /**
     * Build a Monday-Friday schedule grid for the given doctor and week.
     *
     * @return array{
     *     weekStart: CarbonImmutable,
     *     weekEnd: CarbonImmutable,
     *     days: array<int, array{date: string, day: string, num: string, isToday: bool}>,
     *     times: array<string, string>,
     *     grid: array<string, array<string, array{state: string, label: string, sub: ?string, scheduleId: ?int, appointmentId: ?int}>>
     * }
     */
    public function buildWeek(Doctor $doctor, CarbonImmutable $anchorDate): array
    {
        $weekStart = $anchorDate->startOfWeek();
        $weekEnd = $weekStart->addDays(4);
        $today = CarbonImmutable::today()->toDateString();

        $days = [];
        for ($i = 0; $i < 5; $i++) {
            $day = $weekStart->addDays($i);
            $days[] = [
                'date' => $day->toDateString(),
                'day' => $day->format('D'),
                'num' => $day->format('d'),
                'isToday' => $day->toDateString() === $today,
            ];
        }

        $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
            ->whereBetween('schedule_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->get()
            ->groupBy(fn (DoctorSchedule $schedule): string => $schedule->schedule_date->toDateString());

        $appointments = Appointment::with('patient:id,full_name')
            ->where('doctor_id', $doctor->id)
            ->whereBetween('appointment_date', [$weekStart->toDateString(), $weekEnd->toDateString()])
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value, AppointmentStatus::NoShow->value])
            ->get()
            ->groupBy(fn (Appointment $appointment): string => $appointment->appointment_date->toDateString());

        $times = $this->hourlySlots();
        $grid = [];

        foreach ($days as $day) {
            $date = $day['date'];
            $daySchedules = $schedules->get($date, collect())->keyBy('start_time');
            $dayAppointments = $appointments->get($date, collect());

            foreach ($times as $timeKey => $timeLabel) {
                $hourPrefix = substr($timeKey, 0, 2);
                /** @var Appointment|null $matchedAppointment */
                $matchedAppointment = $dayAppointments->first(
                    fn (Appointment $appt): bool => substr($appt->start_time, 0, 2) === $hourPrefix
                );

                if ($matchedAppointment !== null) {
                    $grid[$date][$timeKey] = [
                        'state' => 'booked',
                        'label' => $matchedAppointment->patient->full_name,
                        'sub' => $matchedAppointment->startTimeFormatted(),
                        'scheduleId' => null,
                        'appointmentId' => $matchedAppointment->id,
                    ];

                    continue;
                }

                /** @var DoctorSchedule|null $schedule */
                $schedule = $daySchedules->get($timeKey);

                if ($schedule !== null && $schedule->status === 'blocked') {
                    $grid[$date][$timeKey] = [
                        'state' => 'blocked',
                        'label' => $schedule->note ?: 'Blocked',
                        'sub' => null,
                        'scheduleId' => $schedule->id,
                        'appointmentId' => null,
                    ];

                    continue;
                }

                $grid[$date][$timeKey] = [
                    'state' => 'available',
                    'label' => 'Available',
                    'sub' => null,
                    'scheduleId' => $schedule?->id,
                    'appointmentId' => null,
                ];
            }
        }

        return [
            'weekStart' => $weekStart,
            'weekEnd' => $weekEnd,
            'days' => $days,
            'times' => $times,
            'grid' => $grid,
        ];
    }

    /**
     * @return array<string, string>
     */
    public function bookingSlotTimes(): array
    {
        return [
            '08:00:00' => '8:00 AM',
            '08:30:00' => '8:30 AM',
            '09:00:00' => '9:00 AM',
            '09:30:00' => '9:30 AM',
            '10:00:00' => '10:00 AM',
            '10:30:00' => '10:30 AM',
            '11:00:00' => '11:00 AM',
            '11:30:00' => '11:30 AM',
            '13:00:00' => '1:00 PM',
            '13:30:00' => '1:30 PM',
            '14:00:00' => '2:00 PM',
            '14:30:00' => '2:30 PM',
        ];
    }

    /**
     * Build 30-minute booking time slots for a doctor on a given date.
     *
     * @return array<int, array{time: string, label: string, disabled: bool, reason: ?string}>
     */
    public function availableBookingSlots(Doctor $doctor, string $date): array
    {
        $slotTimes = $this->bookingSlotTimes();

        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value, AppointmentStatus::NoShow->value])
            ->pluck('start_time')
            ->all();

        $blockedHours = DoctorSchedule::where('doctor_id', $doctor->id)
            ->whereDate('schedule_date', $date)
            ->where('status', 'blocked')
            ->pluck('start_time')
            ->map(fn (string $time): string => substr($time, 0, 2))
            ->all();

        $slots = [];

        foreach ($slotTimes as $time => $label) {
            $isBooked = in_array($time, $bookedTimes, true);
            $isBlocked = in_array(substr($time, 0, 2), $blockedHours, true);

            $slots[] = [
                'time' => $time,
                'label' => $label,
                'disabled' => $isBooked || $isBlocked,
                'reason' => $isBooked ? 'Booked' : ($isBlocked ? 'Blocked' : null),
            ];
        }

        return $slots;
    }

    /**
     * Build 30-minute booking time slots across all active doctors for a medical concern/category.
     *
     * @return array<int, array{time: string, label: string, disabled: bool, reason: ?string}>
     */
    public function availableBookingSlotsForSpecialization(string $specialization, string $date): array
    {
        $doctors = Doctor::where('is_active', true)
            ->where('specialization', $specialization)
            ->get(['id']);

        $slotTimes = $this->bookingSlotTimes();

        if ($doctors->isEmpty()) {
            return collect($slotTimes)
                ->map(fn (string $label, string $time): array => [
                    'time' => $time,
                    'label' => $label,
                    'disabled' => true,
                    'reason' => 'Unavailable',
                ])
                ->values()
                ->all();
        }

        $doctorIds = $doctors->pluck('id');

        $bookedByDoctor = Appointment::whereIn('doctor_id', $doctorIds)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', [AppointmentStatus::Cancelled->value, AppointmentStatus::NoShow->value])
            ->get(['doctor_id', 'start_time'])
            ->groupBy('doctor_id')
            ->map(fn (Collection $items): array => $items->pluck('start_time')->all());

        $blockedByDoctor = DoctorSchedule::whereIn('doctor_id', $doctorIds)
            ->whereDate('schedule_date', $date)
            ->where('status', 'blocked')
            ->get(['doctor_id', 'start_time'])
            ->groupBy('doctor_id')
            ->map(fn (Collection $items): array => $items->pluck('start_time')
                ->map(fn (string $time): string => substr($time, 0, 2))
                ->all());

        $slots = [];

        foreach ($slotTimes as $time => $label) {
            $hour = substr($time, 0, 2);
            $hasAvailableDoctor = $doctorIds->contains(function (int $doctorId) use ($time, $hour, $bookedByDoctor, $blockedByDoctor): bool {
                $isBooked = in_array($time, $bookedByDoctor->get($doctorId, []), true);
                $isBlocked = in_array($hour, $blockedByDoctor->get($doctorId, []), true);

                return ! $isBooked && ! $isBlocked;
            });

            $slots[] = [
                'time' => $time,
                'label' => $label,
                'disabled' => ! $hasAvailableDoctor,
                'reason' => $hasAvailableDoctor ? null : 'Full',
            ];
        }

        return $slots;
    }

    /**
     * Find all active doctors for a medical concern who are free at the given date and time.
     *
     * @return Collection<int, Doctor>
     */
    public function findAvailableDoctorsForSlot(string $specialization, string $date, string $time): Collection
    {
        $hourTime = substr($time, 0, 2).':00:00';

        return Doctor::where('is_active', true)
            ->where('specialization', $specialization)
            ->whereDoesntHave('appointments', function ($query) use ($date, $time): void {
                $query->whereDate('appointment_date', $date)
                    ->where('start_time', $time)
                    ->whereNotIn('status', [AppointmentStatus::Cancelled->value, AppointmentStatus::NoShow->value]);
            })
            ->whereDoesntHave('schedules', function ($query) use ($date, $time, $hourTime): void {
                $query->whereDate('schedule_date', $date)
                    ->whereIn('start_time', array_unique([$time, $hourTime]))
                    ->where('status', 'blocked');
            })
            ->get();
    }
}
