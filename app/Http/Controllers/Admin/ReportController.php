<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    public function __invoke(Request $request): View
    {
        $reportType = $request->string('type')->toString() === 'workload' ? 'workload' : 'appointments';
        $from = $request->string('from')->toString() ?: CarbonImmutable::today()->subDays(30)->toDateString();
        $to = $request->string('to')->toString() ?: CarbonImmutable::today()->addDays(7)->toDateString();
        $statusFilter = $request->string('status')->toString() ?: 'all';

        $query = Appointment::with('doctor:id,full_name,specialization')
            ->whereBetween('appointment_date', [$from, $to]);

        if ($statusFilter !== 'all') {
            $query->where('status', $statusFilter);
        }

        $appointments = $query->get();
        $total = $appointments->count();
        $completed = $appointments->where('status', AppointmentStatus::Completed)->count();
        $confirmed = $appointments->where('status', AppointmentStatus::Confirmed)->count();
        $cancelled = $appointments->where('status', AppointmentStatus::Cancelled)->count();
        $noShow = $appointments->where('status', AppointmentStatus::NoShow)->count();

        $completionRate = $total > 0 ? round(($completed / $total) * 100, 1) : 0.0;
        $noShowRate = $total > 0 ? round(($noShow / $total) * 100, 1) : 0.0;

        $dailyRows = $appointments
            ->groupBy(fn (Appointment $appt): string => $appt->appointment_date->format('M j, Y'))
            ->map(function ($group, string $dateLabel): array {
                $scheduled = $group->count();
                $comp = $group->where('status', AppointmentStatus::Completed)->count();
                $canc = $group->whereIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::NoShow])->count();

                return [
                    'label' => $dateLabel,
                    'scheduled' => $scheduled,
                    'completed' => $comp,
                    'cancelled' => $canc,
                    'rate' => $scheduled > 0 ? (int) round(($comp / $scheduled) * 100) : 0,
                ];
            })
            ->values();

        $doctors = Doctor::orderBy('full_name')->get();
        $doctorRows = $doctors->map(function (Doctor $doc) use ($appointments): array {
            $docAppts = $appointments->where('doctor_id', $doc->id);
            $scheduled = $docAppts->count();
            $comp = $docAppts->where('status', AppointmentStatus::Completed)->count();
            $canc = $docAppts->whereIn('status', [AppointmentStatus::Cancelled, AppointmentStatus::NoShow])->count();

            return [
                'label' => $doc->full_name,
                'scheduled' => $scheduled,
                'completed' => $comp,
                'cancelled' => $canc,
                'rate' => $scheduled > 0 ? (int) round(($comp / $scheduled) * 100) : 0,
            ];
        });

        $totalMinutes = $appointments->where('status', AppointmentStatus::Completed)->sum('duration_minutes');
        $activeDoctorCount = max(1, $doctors->where('is_active', true)->count());

        $safeTotal = max(1, $total);
        $pGreen = round(($completed / $safeTotal) * 100, 1);
        $pTeal = round(($confirmed / $safeTotal) * 100, 1);
        $pRed = round(($cancelled / $safeTotal) * 100, 1);

        return view('admin.reports.index', [
            'reportType' => $reportType,
            'from' => $from,
            'to' => $to,
            'statusFilter' => $statusFilter,
            'statuses' => AppointmentStatus::cases(),
            'total' => $total,
            'completed' => $completed,
            'confirmed' => $confirmed,
            'cancelled' => $cancelled,
            'noShow' => $noShow,
            'completionRate' => $completionRate,
            'noShowRate' => $noShowRate,
            'clinicalHours' => (int) round($totalMinutes / 60),
            'avgPerDoctor' => round($total / $activeDoctorCount, 1),
            'uniquePatients' => $appointments->pluck('patient_id')->unique()->count(),
            'tableRows' => $reportType === 'appointments' ? $dailyRows : $doctorRows,
            'donutStops' => [
                'greenEnd' => $pGreen,
                'tealEnd' => $pGreen + $pTeal,
                'redEnd' => $pGreen + $pTeal + $pRed,
            ],
        ]);
    }
}
