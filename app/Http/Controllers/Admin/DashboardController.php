<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\CarbonImmutable;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $today = CarbonImmutable::today();
        $yesterday = $today->subDay();
        $weekStart = $today->startOfWeek();
        $weekEnd = $weekStart->addDays(6);

        $todayCount = Appointment::whereDate('appointment_date', $today)->count();
        $yesterdayCount = Appointment::whereDate('appointment_date', $yesterday)->count();
        $pendingCount = Appointment::where('status', AppointmentStatus::Pending)->count();
        $completedToday = Appointment::whereDate('appointment_date', $today)
            ->where('status', AppointmentStatus::Completed)
            ->count();
        $cancelledOrNoShowToday = Appointment::whereDate('appointment_date', $today)
            ->whereIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::NoShow])
            ->count();

        $weekAppointments = Appointment::whereBetween('appointment_date', [
            $weekStart->toDateString(),
            $weekEnd->toDateString(),
        ])->get(['id', 'appointment_date']);

        $dailyChart = [];
        for ($i = 0; $i < 7; $i++) {
            $day = $weekStart->addDays($i);
            $count = $weekAppointments->filter(
                fn (Appointment $appt): bool => $appt->appointment_date->toDateString() === $day->toDateString()
            )->count();

            $dailyChart[] = [
                'label' => $day->format('D'),
                'count' => $count,
            ];
        }

        $maxChartCount = max(1, collect($dailyChart)->max('count'));

        $upcomingAppointments = Appointment::with(['patient:id,full_name,avatar_tone', 'doctor:id,full_name'])
            ->whereDate('appointment_date', '>=', $today)
            ->whereNotIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::NoShow])
            ->orderBy('appointment_date')
            ->orderBy('start_time')
            ->limit(4)
            ->get();

        return view('admin.dashboard', [
            'todayCount' => $todayCount,
            'yesterdayDiff' => $todayCount - $yesterdayCount,
            'pendingCount' => $pendingCount,
            'completedToday' => $completedToday,
            'completionRate' => $todayCount > 0 ? (int) round(($completedToday / $todayCount) * 100) : 0,
            'cancelledOrNoShowToday' => $cancelledOrNoShowToday,
            'dailyChart' => $dailyChart,
            'maxChartCount' => $maxChartCount,
            'weeklyTotal' => $weekAppointments->count(),
            'upcomingAppointments' => $upcomingAppointments,
        ]);
    }
}
