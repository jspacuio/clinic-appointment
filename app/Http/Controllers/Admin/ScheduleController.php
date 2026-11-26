<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request): View
    {
        $doctors = Doctor::orderBy('full_name')->get();
        $selectedDoctorId = $request->integer('doctor_id') ?: ($doctors->first()?->id ?? 0);
        $selectedDoctor = $doctors->firstWhere('id', $selectedDoctorId) ?? $doctors->first();

        $weekParam = $request->string('week')->toString();
        $anchorDate = $weekParam !== '' ? CarbonImmutable::parse($weekParam) : CarbonImmutable::today();

        $calendar = $selectedDoctor !== null
            ? $this->builder->buildWeek($selectedDoctor, $anchorDate)
            : null;

        return view('admin.schedules.index', [
            'doctors' => $doctors,
            'selectedDoctor' => $selectedDoctor,
            'calendar' => $calendar,
            'hourlySlots' => $this->builder->hourlySlots(),
        ]);
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $doctor = Doctor::findOrFail($request->integer('doctor_id'));
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
            'Updated schedule slot',
            "{$doctor->full_name} · {$schedule->schedule_date->format('M j, Y')}",
            ucfirst($schedule->status).' at '.CarbonImmutable::parse($startTime)->format('g:i A')
        );

        return redirect()
            ->route('admin.schedules.index', [
                'doctor_id' => $doctor->id,
                'week' => $schedule->schedule_date->startOfWeek()->toDateString(),
            ])
            ->with('status', 'Doctor schedule updated.');
    }

    public function destroy(DoctorSchedule $schedule): RedirectResponse
    {
        $doctorId = $schedule->doctor_id;
        $week = $schedule->schedule_date->startOfWeek()->toDateString();
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index', ['doctor_id' => $doctorId, 'week' => $week])
            ->with('status', 'Schedule block removed.');
    }
}
