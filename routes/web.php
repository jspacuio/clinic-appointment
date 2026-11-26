<?php

<<<<<<< Updated upstream
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\PatientController;
=======
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\AuditLogController as AdminAuditLogController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController as AdminAuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\ScheduleController as AdminScheduleController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Doctor\AppointmentController as DoctorAppointmentController;
use App\Http\Controllers\Doctor\Auth\AuthenticatedSessionController as DoctorAuthController;
use App\Http\Controllers\Doctor\DashboardController as DoctorDashboardController;
use App\Http\Controllers\Doctor\PatientController as DoctorPatientController;
use App\Http\Controllers\Doctor\ScheduleController as DoctorScheduleController;
use App\Http\Controllers\Patient\AppointmentController as PatientAppointmentController;
use App\Http\Controllers\Patient\Auth\AuthenticatedSessionController as PatientAuthController;
use App\Http\Controllers\Patient\Auth\RegisteredUserController as PatientRegisterController;
use App\Http\Controllers\Patient\DashboardController as PatientDashboardController;
use App\Http\Controllers\Patient\ProfileController as PatientProfileController;
>>>>>>> Stashed changes
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicSiteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicSiteController::class, 'home'])->name('home');
Route::get('/doctors', [PublicSiteController::class, 'doctors'])->name('public.doctors');
Route::get('/services', [PublicSiteController::class, 'services'])->name('public.services');

Route::get('/dashboard', function (Request $request) {
    if ($request->user()?->isDoctor()) {
        return redirect()->route('doctor.dashboard');
    }

    if ($request->user()?->isPatient()) {
        return redirect()->route('patient.dashboard');
    }

    return redirect()->route('admin.dashboard');
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

/*
|--------------------------------------------------------------------------
| Admin Portal Routes (Admin & Receptionist)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('login', [AdminAuthController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'role:admin,receptionist'])->group(function () {
        Route::post('logout', [AdminAuthController::class, 'destroy'])->name('logout');

        Route::get('dashboard', AdminDashboardController::class)->name('dashboard');

        Route::get('appointments', [AdminAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/create', [AdminAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [AdminAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{appointment}', [AdminAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('appointments/{appointment}/status', [AdminAppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::patch('appointments/{appointment}/reschedule', [AdminAppointmentController::class, 'reschedule'])->name('appointments.reschedule');
        Route::patch('appointments/{appointment}/cancel', [AdminAppointmentController::class, 'cancel'])->name('appointments.cancel');

        Route::get('schedules', [AdminScheduleController::class, 'index'])->name('schedules.index');
        Route::post('schedules', [AdminScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('schedules/{schedule}', [AdminScheduleController::class, 'destroy'])->name('schedules.destroy');

        Route::get('patients', [AdminPatientController::class, 'index'])->name('patients.index');
        Route::post('patients', [AdminPatientController::class, 'store'])->name('patients.store');
        Route::get('patients/{patient}', [AdminPatientController::class, 'show'])->name('patients.show');
        Route::put('patients/{patient}', [AdminPatientController::class, 'update'])->name('patients.update');

        Route::get('doctors', [AdminDoctorController::class, 'index'])->name('doctors.index');
        Route::post('doctors', [AdminDoctorController::class, 'store'])->name('doctors.store');
        Route::put('doctors/{doctor}', [AdminDoctorController::class, 'update'])->name('doctors.update');
        Route::patch('doctors/{doctor}/toggle', [AdminDoctorController::class, 'toggle'])->name('doctors.toggle');

        Route::get('reports', AdminReportController::class)->name('reports.index');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users', [AdminUserController::class, 'store'])->name('users.store');
        Route::patch('users/{user}/toggle', [AdminUserController::class, 'toggle'])->name('users.toggle');

        Route::get('audit', AdminAuditLogController::class)->name('audit.index');

        Route::view('style-guide', 'admin.style-guide')->name('style-guide');
    });
});

/*
|--------------------------------------------------------------------------
| Doctor Portal Routes (Doctors Only)
|--------------------------------------------------------------------------
*/
Route::prefix('doctor')->name('doctor.')->group(function () {
    Route::get('login', [DoctorAuthController::class, 'create'])->name('login');
    Route::post('login', [DoctorAuthController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'role:doctor'])->group(function () {
        Route::post('logout', [DoctorAuthController::class, 'destroy'])->name('logout');

        Route::get('dashboard', DoctorDashboardController::class)->name('dashboard');

        Route::get('appointments', [DoctorAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/{appointment}', [DoctorAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('appointments/{appointment}/status', [DoctorAppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::patch('appointments/{appointment}/notes', [DoctorAppointmentController::class, 'updateNotes'])->name('appointments.notes');
        Route::patch('appointments/{appointment}/cancel', [DoctorAppointmentController::class, 'cancel'])->name('appointments.cancel');

        Route::get('schedules', [DoctorScheduleController::class, 'index'])->name('schedules.index');
        Route::post('schedules', [DoctorScheduleController::class, 'store'])->name('schedules.store');
        Route::delete('schedules/{schedule}', [DoctorScheduleController::class, 'destroy'])->name('schedules.destroy');

        Route::get('patients', [DoctorPatientController::class, 'index'])->name('patients.index');
        Route::get('patients/{patient}', [DoctorPatientController::class, 'show'])->name('patients.show');
    });
});

/*
|--------------------------------------------------------------------------
| Patient Portal Routes (Public Registration & Patients)
|--------------------------------------------------------------------------
*/
Route::prefix('patient')->name('patient.')->group(function () {
    Route::get('login', [PatientAuthController::class, 'create'])->name('login');
    Route::post('login', [PatientAuthController::class, 'store'])->name('login.store');
    Route::get('register', [PatientRegisterController::class, 'create'])->name('register');
    Route::post('register', [PatientRegisterController::class, 'store'])->name('register.store');

    Route::middleware(['auth', 'role:patient'])->group(function () {
        Route::post('logout', [PatientAuthController::class, 'destroy'])->name('logout');

        Route::get('dashboard', PatientDashboardController::class)->name('dashboard');

        Route::get('appointments', [PatientAppointmentController::class, 'index'])->name('appointments.index');
        Route::get('appointments/create', [PatientAppointmentController::class, 'create'])->name('appointments.create');
        Route::post('appointments', [PatientAppointmentController::class, 'store'])->name('appointments.store');
        Route::get('appointments/{appointment}', [PatientAppointmentController::class, 'show'])->name('appointments.show');
        Route::patch('appointments/{appointment}/status', [PatientAppointmentController::class, 'updateStatus'])->name('appointments.status');
        Route::patch('appointments/{appointment}/cancel', [PatientAppointmentController::class, 'cancel'])->name('appointments.cancel');

        Route::get('categories/slots', [PatientAppointmentController::class, 'slots'])->name('categories.slots');

        Route::get('profile', [PatientProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [PatientProfileController::class, 'update'])->name('profile.update');
    });
});

require __DIR__.'/auth.php';
