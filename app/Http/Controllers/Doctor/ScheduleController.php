<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreScheduleRequest;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Services\WeeklyScheduleBuilder;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function __construct(private readonly WeeklyScheduleBuilder $builder) {}

    private function resolveDoctor(Request $request): Doctor
    {
        $doctor = $request->user()->doctor;
        abort_if($doctor === null, 403, 'No doctor profile is linked to this account.');

        return $doctor;
    }

    public function index(Request $request): View
    {
        $doctor = $this->resolveDoctor($request);
        $weekParam = $request->string('week')->toString();
        $anchorDate = $weekParam !== '' ? CarbonImmutable::parse($weekParam) : CarbonImmutable::today();

        $calendar = $this->builder->buildWeek($doctor, $anchorDate);

        return view('doctor.schedules.index', [
            'doctor' => $doctor,
            'calendar' => $calendar,
            'hourlySlots' => $this->builder->hourlySlots(),
        ]);
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $doctor = $this->resolveDoctor($request);
        $startTime = $request->string('start_time')->toString();
        $endTime = CarbonImmutable::parse($startTime)->addMinutes($doctor->consultation_duration)->format('H:i:s');

        $schedule = DoctorSchedule::updateOrCreate(
            [
                'doctor_id' => $doctor->id,
                'schedule_date' => $request->string('schedule_date')->toString(),
                'start_time' => $startTime,
            ],
            [
                'end_time' => $endTime,
                'status' => $request->string('status')->toString(),
                'note' => $request->input('note'),
            ]
        );

        AuditLog::record(
            $request->user(),
            'Updated own schedule',
            "{$doctor->full_name} · {$schedule->schedule_date->format('M j, Y')}",
            ucfirst($schedule->status).' at '.CarbonImmutable::parse($startTime)->format('g:i A')
        );

        return redirect()
            ->route('doctor.schedules.index', ['week' => $schedule->schedule_date->startOfWeek()->toDateString()])
            ->with('status', 'Your schedule has been updated.');
    }

    public function destroy(Request $request, DoctorSchedule $schedule): RedirectResponse
    {
        $doctor = $this->resolveDoctor($request);

        if ($schedule->doctor_id !== $doctor->id) {
            abort(404);
        }

        $week = $schedule->schedule_date->startOfWeek()->toDateString();
        $schedule->delete();

        return redirect()
            ->route('doctor.schedules.index', ['week' => $week])
            ->with('status', 'Schedule slot cleared.');
    }
}
