<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/role/{role}', function (string $role) {
        session(['clinic_role' => $role === 'Doctor' ? 'Doctor' : 'Admin']);
        return redirect()->route('dashboard');
    })->name('clinic.role');

    Route::get('/patients', [PatientController::class, 'index'])->name('clinic.patients');
    Route::post('/patients', [PatientController::class, 'store'])->name('clinic.patients.store');
    Route::get('/patients/{patient}', [PatientController::class, 'show'])->name('clinic.patients.show');
    Route::patch('/patients/{patient}', [PatientController::class, 'update'])->name('clinic.patients.update');
    Route::delete('/patients/{patient}', [PatientController::class, 'destroy'])->name('clinic.patients.destroy');
    Route::view('/doctors', 'clinic.doctors')->name('clinic.doctors');
    Route::view('/schedules', 'clinic.schedules')->name('clinic.schedules');
    Route::get('/book', function (\Illuminate\Http\Request $request) {
        $patients = \App\Models\Patient::query()->latest()->get();

        return view('clinic.book', [
            'patients' => $patients,
            'selectedPatientId' => $request->query('patient_id'),
        ]);
    })->name('clinic.book');
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('clinic.appointments');
    Route::post('/appointments', [AppointmentController::class, 'store'])->name('clinic.appointments.store');
    Route::get('/appointments/{appointment}', [AppointmentController::class, 'show'])->name('clinic.appointments.show');
    Route::patch('/appointments/{appointment}', [AppointmentController::class, 'update'])->name('clinic.appointments.update');
    Route::view('/reports', 'clinic.reports')->name('clinic.reports');
    Route::view('/users', 'clinic.users')->name('clinic.users');
    Route::view('/audit', 'clinic.audit')->name('clinic.audit');
    Route::view('/style-guide', 'clinic.style-guide')->name('clinic.style');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
