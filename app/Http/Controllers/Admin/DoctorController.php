<?php

namespace App\Http\Controllers\Admin;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreDoctorRequest;
use App\Http\Requests\Admin\UpdateDoctorRequest;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class DoctorController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim($request->string('search')->toString());
        $status = $request->string('status')->toString() ?: 'all';
        $today = CarbonImmutable::today()->toDateString();

        $query = Doctor::withCount([
            'appointments as total_appointments_count' => fn ($q) => $q->whereNotIn('status', [AppointmentStatus::Cancelled->value]),
            'appointments as today_appointments_count' => fn ($q) => $q->whereDate('appointment_date', $today)->whereNotIn('status', [AppointmentStatus::Cancelled->value]),
        ]);

        if ($search !== '') {
            $query->where(function ($q) use ($search): void {
                $q->where('full_name', 'like', "%{$search}%")
                    ->orWhere('specialization', 'like', "%{$search}%")
                    ->orWhere('license_number', 'like', "%{$search}%");
            });
        }

        if ($status === 'active') {
            $query->where('is_active', true);
        } elseif ($status === 'inactive') {
            $query->where('is_active', false);
        }

        $doctors = $query->orderBy('full_name')->get();

        return view('admin.doctors.index', [
            'doctors' => $doctors,
            'search' => $search,
            'status' => $status,
        ]);
    }

    public function store(StoreDoctorRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $tones = ['teal', 'blue', 'purple', 'amber'];

        $doctor = DB::transaction(function () use ($validated, $tones): Doctor {
            $user = User::create([
                'name' => $validated['full_name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password'] ?? 'ClinicCare2025'),
                'role' => UserRole::Doctor,
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            return Doctor::create([
                'user_id' => $user->id,
                'full_name' => $validated['full_name'],
                'specialization' => $validated['specialization'],
                'license_number' => $validated['license_number'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'consultation_duration' => (int) $validated['consultation_duration'],
                'availability_summary' => $validated['availability_summary'] ?? 'Mon–Fri · 8:00 AM–4:00 PM',
                'avatar_tone' => $tones[array_rand($tones)],
                'is_active' => true,
            ]);
        });

        AuditLog::record(
            $request->user(),
            'Added doctor',
            "Doctor · {$doctor->full_name}",
            "{$doctor->specialization} ({$doctor->license_number})"
        );

        return redirect()
            ->route('admin.doctors.index')
            ->with('status', "{$doctor->full_name} added with Doctor Portal login.");
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($doctor, $validated): void {
            $doctor->update([
                'full_name' => $validated['full_name'],
                'specialization' => $validated['specialization'],
                'license_number' => $validated['license_number'],
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'],
                'consultation_duration' => (int) $validated['consultation_duration'],
                'availability_summary' => $validated['availability_summary'] ?? $doctor->availability_summary,
            ]);

            if ($doctor->user !== null) {
                $doctor->user->update([
                    'name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                ]);
            }
        });

        AuditLog::record(
            $request->user(),
            'Updated doctor',
            "Doctor · {$doctor->full_name}",
            "Updated specialization/profile ({$doctor->specialization})"
        );

        return redirect()
            ->route('admin.doctors.index')
            ->with('status', "{$doctor->full_name} updated.");
    }

    public function toggle(Request $request, Doctor $doctor): RedirectResponse
    {
        $newState = ! $doctor->is_active;

        DB::transaction(function () use ($doctor, $newState): void {
            $doctor->update(['is_active' => $newState]);

            if ($doctor->user !== null) {
                $doctor->user->update(['is_active' => $newState]);
            }
        });

        AuditLog::record(
            $request->user(),
            'Changed doctor status',
            "Doctor · {$doctor->full_name}",
            ($newState ? 'Inactive → Active' : 'Active → Inactive')
        );

        return redirect()
            ->route('admin.doctors.index')
            ->with('status', "{$doctor->full_name} is now ".($newState ? 'Active' : 'Inactive').'.');
    }
}
