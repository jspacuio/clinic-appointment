<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status', 'All');
        $search = trim((string) $request->query('search', ''));
        $fromDate = $request->query('from_date');
        $toDate = $request->query('to_date');

        $query = Appointment::query()->with('patient')->latest('appointment_date')->latest('id');

        if ($statusFilter && $statusFilter !== 'All') {
            $query->where('status', $statusFilter);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('appointment_code', 'like', "%{$search}%")
                    ->orWhere('doctor_name', 'like', "%{$search}%")
                    ->orWhereHas('patient', function ($pq) use ($search) {
                        $pq->where('full_name', 'like', "%{$search}%")
                            ->orWhere('patient_code', 'like', "%{$search}%");
                    });
            });
        }

        if ($fromDate) {
            $query->whereDate('appointment_date', '>=', $fromDate);
        }

        if ($toDate) {
            $query->whereDate('appointment_date', '<=', $toDate);
        }

        $appointments = $query->paginate(10)->withQueryString();

        $counts = Appointment::query()
            ->selectRaw('status, count(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        return view('clinic.appointments', [
            'appointments' => $appointments,
            'statusCounts' => $counts,
            'totalCount' => $counts->sum(),
            'activeStatus' => $statusFilter ?: 'All',
            'filters' => [
                'search' => $search,
                'from_date' => $fromDate,
                'to_date' => $toDate,
            ],
        ]);
    }

    public function store(StoreAppointmentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['appointment_code'] = Appointment::generateCode();
        $data['specialty'] = ($data['specialty'] ?? null) ?: 'General Medicine';
        $data['visit_type'] = ($data['visit_type'] ?? null) ?: 'General Checkup';
        $data['status'] = 'Confirmed';
        $data['booked_by'] = $request->user()?->name ?? 'Admin';

        $appointment = Appointment::create($data);
        $appointment->load('patient');

        return redirect()
            ->route('clinic.appointments')
            ->with('status', "Appointment {$appointment->appointment_code} booked for {$appointment->patient->full_name}.");
    }

    public function show(Appointment $appointment): View
    {
        $appointment->load('patient');

        return view('clinic.appointment-show', [
            'appointment' => $appointment,
        ]);
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment): RedirectResponse
    {
        $appointment->update($request->validated());

        return redirect()
            ->route('clinic.appointments.show', $appointment)
            ->with('status', "Appointment {$appointment->appointment_code} was updated.");
    }
}
