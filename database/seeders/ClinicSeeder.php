<?php

namespace Database\Seeders;

use App\Enums\AppointmentStatus;
use App\Enums\UserRole;
use App\Models\Appointment;
use App\Models\AuditLog;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Patient;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('ClinicCare2025');
        $now = CarbonImmutable::now();
        $today = $now->toDateString();

        $admin = User::updateOrCreate(
            ['email' => 'admin@cliniccare.com'],
            [
                'name' => 'Anna Reyes',
                'password' => $password,
                'role' => UserRole::Admin,
                'phone' => '(555) 201-4400',
                'is_active' => true,
                'email_verified_at' => $now,
                'last_active_at' => $now->subMinutes(12),
            ]
        );

        $receptionist = User::updateOrCreate(
            ['email' => 'mia@cliniccare.com'],
            [
                'name' => 'Mia Brooks',
                'password' => $password,
                'role' => UserRole::Receptionist,
                'phone' => '(555) 201-4401',
                'is_active' => true,
                'email_verified_at' => $now,
                'last_active_at' => $now->subMinutes(28),
            ]
        );

        $doctorsData = [
            [
                'email' => 'elena@cliniccare.com',
                'full_name' => 'Dr. Elena Morris',
                'specialization' => 'General Medicine',
                'license_number' => 'MD-482910',
                'phone' => '(555) 310-1001',
                'consultation_duration' => 30,
                'availability_summary' => 'Mon–Fri · 8:00 AM–4:00 PM',
                'avatar_tone' => 'teal',
                'is_active' => true,
            ],
            [
                'email' => 'james@cliniccare.com',
                'full_name' => 'Dr. James Chen',
                'specialization' => 'Pediatrics',
                'license_number' => 'MD-519042',
                'phone' => '(555) 310-1002',
                'consultation_duration' => 30,
                'availability_summary' => 'Mon, Tue, Thu · 9:00 AM–5:00 PM',
                'avatar_tone' => 'blue',
                'is_active' => true,
            ],
            [
                'email' => 'priya@cliniccare.com',
                'full_name' => 'Dr. Priya Shah',
                'specialization' => 'Dermatology',
                'license_number' => 'MD-604218',
                'phone' => '(555) 310-1003',
                'consultation_duration' => 30,
                'availability_summary' => 'Wed–Sat · 10:00 AM–6:00 PM',
                'avatar_tone' => 'purple',
                'is_active' => true,
            ],
            [
                'email' => 'marcus@cliniccare.com',
                'full_name' => 'Dr. Marcus Lee',
                'specialization' => 'Cardiology',
                'license_number' => 'MD-398112',
                'phone' => '(555) 310-1004',
                'consultation_duration' => 45,
                'availability_summary' => 'Tue–Fri · 8:00 AM–3:00 PM',
                'avatar_tone' => 'amber',
                'is_active' => false,
            ],
            [
                'email' => 'sofia@cliniccare.com',
                'full_name' => 'Dr. Sofia Alvarez',
                'specialization' => 'Eye Care',
                'license_number' => 'MD-712340',
                'phone' => '(555) 310-1005',
                'consultation_duration' => 30,
                'availability_summary' => 'Mon–Fri · 8:00 AM–5:00 PM',
                'avatar_tone' => 'teal',
                'is_active' => true,
            ],
        ];

        $doctors = [];

        foreach ($doctorsData as $doc) {
            $user = User::updateOrCreate(
                ['email' => $doc['email']],
                [
                    'name' => $doc['full_name'],
                    'password' => $password,
                    'role' => UserRole::Doctor,
                    'phone' => $doc['phone'],
                    'is_active' => $doc['is_active'],
                    'email_verified_at' => $now,
                    'last_active_at' => $doc['is_active'] ? $now->subMinutes(20) : $now->subDays(17),
                ]
            );

            $doctors[$doc['license_number']] = Doctor::updateOrCreate(
                ['license_number' => $doc['license_number']],
                [
                    'user_id' => $user->id,
                    'full_name' => $doc['full_name'],
                    'specialization' => $doc['specialization'],
                    'phone' => $doc['phone'],
                    'email' => $doc['email'],
                    'consultation_duration' => $doc['consultation_duration'],
                    'availability_summary' => $doc['availability_summary'],
                    'avatar_tone' => $doc['avatar_tone'],
                    'is_active' => $doc['is_active'],
                ]
            );
        }

        $patientsData = [
            [
                'patient_code' => 'PC-1042',
                'full_name' => 'Sophia Carter',
                'date_of_birth' => '1991-04-08',
                'sex' => 'Female',
                'phone' => '(555) 284-0193',
                'email' => 'sophia.carter@email.com',
                'emergency_contact' => 'Daniel Carter · (555) 284-0194',
                'address' => '124 Cedar Lane, Brookfield',
                'allergy_note' => 'Penicillin allergy',
                'allergy_recorded_at' => '2023-10-14',
                'avatar_tone' => 'purple',
                'is_active' => true,
            ],
            [
                'patient_code' => 'PC-1041',
                'full_name' => 'Liam Wilson',
                'date_of_birth' => '1983-09-17',
                'sex' => 'Male',
                'phone' => '(555) 610-8821',
                'email' => 'liam.wilson@email.com',
                'emergency_contact' => 'Claire Wilson · (555) 610-8822',
                'address' => '88 Oakridge Drive, Brookfield',
                'allergy_note' => null,
                'allergy_recorded_at' => null,
                'avatar_tone' => 'blue',
                'is_active' => true,
            ],
            [
                'patient_code' => 'PC-1040',
                'full_name' => 'Olivia Martinez',
                'date_of_birth' => '1996-02-21',
                'sex' => 'Female',
                'phone' => '(555) 346-1290',
                'email' => 'olivia.martinez@email.com',
                'emergency_contact' => 'Mateo Martinez · (555) 346-1291',
                'address' => '412 Maple Avenue, Brookfield',
                'allergy_note' => 'Latex sensitivity',
                'allergy_recorded_at' => '2024-03-02',
                'avatar_tone' => 'amber',
                'is_active' => true,
            ],
            [
                'patient_code' => 'PC-1039',
                'full_name' => 'Noah Thompson',
                'date_of_birth' => '1974-11-05',
                'sex' => 'Male',
                'phone' => '(555) 732-4611',
                'email' => 'noah.thompson@email.com',
                'emergency_contact' => 'Grace Thompson · (555) 732-4612',
                'address' => '19 Willow Court, Brookfield',
                'allergy_note' => null,
                'allergy_recorded_at' => null,
                'avatar_tone' => 'teal',
                'is_active' => true,
            ],
            [
                'patient_code' => 'PC-1038',
                'full_name' => 'Emma Rodriguez',
                'date_of_birth' => '1988-06-30',
                'sex' => 'Female',
                'phone' => '(555) 920-1158',
                'email' => 'emma.rodriguez@email.com',
                'emergency_contact' => 'Lucas Rodriguez · (555) 920-1159',
                'address' => '75 Birch Street, Brookfield',
                'allergy_note' => null,
                'allergy_recorded_at' => null,
                'avatar_tone' => 'pink',
                'is_active' => false,
            ],
        ];

        $patients = [];

        foreach ($patientsData as $pat) {
            $patientUserId = null;

            if ($pat['patient_code'] === 'PC-1042' && ! empty($pat['email'])) {
                $patientUser = User::updateOrCreate(
                    ['email' => $pat['email']],
                    [
                        'name' => $pat['full_name'],
                        'password' => $password,
                        'role' => UserRole::Patient,
                        'phone' => $pat['phone'],
                        'is_active' => true,
                        'email_verified_at' => $now,
                        'last_active_at' => $now->subMinutes(15),
                    ]
                );
                $patientUserId = $patientUser->id;
            }

            $patients[$pat['patient_code']] = Patient::updateOrCreate(
                ['patient_code' => $pat['patient_code']],
                array_merge($pat, $patientUserId !== null ? ['user_id' => $patientUserId] : [])
            );
        }

        $elena = $doctors['MD-482910'];
        $james = $doctors['MD-519042'];
        $priya = $doctors['MD-604218'];
        $sofia = $doctors['MD-712340'];

        $weekStart = $now->startOfWeek();

        foreach ([$elena, $james, $priya, $sofia] as $doc) {
            for ($dayOffset = 0; $dayOffset < 5; $dayOffset++) {
                $date = $weekStart->addDays($dayOffset)->toDateString();

                DoctorSchedule::updateOrCreate(
                    [
                        'doctor_id' => $doc->id,
                        'schedule_date' => $date,
                        'start_time' => '12:00:00',
                    ],
                    [
                        'end_time' => '13:00:00',
                        'status' => 'blocked',
                        'note' => 'Lunch break',
                    ]
                );
            }
        }

        DoctorSchedule::updateOrCreate(
            [
                'doctor_id' => $elena->id,
                'schedule_date' => $weekStart->addDays(3)->toDateString(),
                'start_time' => '15:00:00',
            ],
            [
                'end_time' => '15:30:00',
                'status' => 'blocked',
                'note' => 'Department review',
            ]
        );

        $appointmentsData = [
            [
                'appointment_code' => 'APT-2054',
                'patient_id' => $patients['PC-1042']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $today,
                'start_time' => '09:00:00',
                'duration_minutes' => 30,
                'visit_type' => 'General Checkup',
                'reason' => 'Annual wellness checkup',
                'status' => AppointmentStatus::Confirmed,
                'doctor_confirmed_at' => $now->subDays(4),
                'patient_confirmed_at' => $now->subDays(4),
                'confirmed_at' => $now->subDays(4),
            ],
            [
                'appointment_code' => 'APT-2055',
                'patient_id' => $patients['PC-1041']->id,
                'doctor_id' => $james->id,
                'booked_by_user_id' => $receptionist->id,
                'appointment_date' => $today,
                'start_time' => '10:30:00',
                'duration_minutes' => 30,
                'visit_type' => 'Follow-up',
                'reason' => 'Blood pressure and medication follow-up',
                'status' => AppointmentStatus::InProgress,
                'doctor_confirmed_at' => $now->subDays(2),
                'patient_confirmed_at' => $now->subDays(2),
                'confirmed_at' => $now->subDays(2),
                'doctor_started_at' => $now->subMinutes(15),
                'patient_started_at' => $now->subMinutes(15),
                'started_at' => $now->subMinutes(15),
            ],
            [
                'appointment_code' => 'APT-2056',
                'patient_id' => $patients['PC-1040']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $today,
                'start_time' => '11:45:00',
                'duration_minutes' => 30,
                'visit_type' => 'Consultation',
                'reason' => 'Persistent migraines over the past two weeks',
                'status' => AppointmentStatus::Pending,
                'patient_confirmed_at' => $now->subHours(4),
            ],
            [
                'appointment_code' => 'APT-2057',
                'patient_id' => $patients['PC-1039']->id,
                'doctor_id' => $priya->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $today,
                'start_time' => '13:30:00',
                'duration_minutes' => 30,
                'visit_type' => 'Dermatology',
                'reason' => 'Skin rash evaluation and prescription renewal',
                'status' => AppointmentStatus::Completed,
                'clinical_notes' => 'Mild contact dermatitis observed on forearm. Prescribed topical hydrocortisone 1% twice daily for 7 days.',
                'doctor_confirmed_at' => $now->subDays(3),
                'patient_confirmed_at' => $now->subDays(3),
                'confirmed_at' => $now->subDays(3),
                'doctor_started_at' => $now->subHours(2),
                'patient_started_at' => $now->subHours(2),
                'started_at' => $now->subHours(2),
                'doctor_completed_at' => $now->subHours(1),
                'patient_completed_at' => $now->subHours(1),
                'completed_at' => $now->subHours(1),
            ],
            [
                'appointment_code' => 'APT-2058',
                'patient_id' => $patients['PC-1038']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $receptionist->id,
                'appointment_date' => $today,
                'start_time' => '15:00:00',
                'duration_minutes' => 30,
                'visit_type' => 'Follow-up',
                'reason' => 'Post-lab results discussion',
                'status' => AppointmentStatus::Cancelled,
                'cancellation_reason' => 'Patient requested',
                'cancellation_notes' => 'Patient requested to reschedule for next week.',
                'confirmed_at' => $now->subDays(2),
                'cancelled_at' => $now->subHours(3),
            ],
            [
                'appointment_code' => 'APT-2059',
                'patient_id' => $patients['PC-1042']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $now->subDays(120)->toDateString(),
                'start_time' => '10:00:00',
                'duration_minutes' => 30,
                'visit_type' => 'General Checkup',
                'reason' => 'Routine wellness examination',
                'status' => AppointmentStatus::Completed,
                'clinical_notes' => 'Routine wellness examination completed. Vitals within normal range.',
                'confirmed_at' => $now->subDays(125),
                'started_at' => $now->subDays(120),
                'completed_at' => $now->subDays(120),
            ],
            [
                'appointment_code' => 'APT-2060',
                'patient_id' => $patients['PC-1041']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $now->subDay()->toDateString(),
                'start_time' => '14:00:00',
                'duration_minutes' => 30,
                'visit_type' => 'Consultation',
                'reason' => 'Seasonal allergy flare-up',
                'status' => AppointmentStatus::Completed,
                'clinical_notes' => 'Recommended non-drowsy antihistamine and nasal spray.',
                'confirmed_at' => $now->subDays(4),
                'started_at' => $now->subDay(),
                'completed_at' => $now->subDay(),
            ],
            [
                'appointment_code' => 'APT-2061',
                'patient_id' => $patients['PC-1040']->id,
                'doctor_id' => $elena->id,
                'booked_by_user_id' => $admin->id,
                'appointment_date' => $now->addDays(2)->toDateString(),
                'start_time' => '10:00:00',
                'duration_minutes' => 30,
                'visit_type' => 'Follow-up',
                'reason' => 'Follow-up review',
                'status' => AppointmentStatus::Confirmed,
                'confirmed_at' => $now->subHour(),
            ],
        ];

        foreach ($appointmentsData as $appt) {
            Appointment::updateOrCreate(
                ['appointment_code' => $appt['appointment_code']],
                $appt
            );
        }

        if (AuditLog::count() === 0) {
            AuditLog::create([
                'user_id' => $admin->id,
                'actor_name' => $admin->name,
                'action' => 'Updated appointment',
                'entity' => 'Appointment #APT-2054',
                'change_summary' => 'Pending → Confirmed',
                'created_at' => $now->subMinutes(25),
            ]);
            AuditLog::create([
                'user_id' => $elena->user_id,
                'actor_name' => $elena->full_name,
                'action' => 'Added consultation note',
                'entity' => 'Patient · Sophia Carter',
                'change_summary' => 'Empty → Clinical note added',
                'created_at' => $now->subMinutes(48),
            ]);
            AuditLog::create([
                'user_id' => $receptionist->id,
                'actor_name' => $receptionist->name,
                'action' => 'Created patient',
                'entity' => 'Patient · Liam Wilson',
                'change_summary' => 'New record created',
                'created_at' => $now->subHours(2),
            ]);
            AuditLog::create([
                'user_id' => $admin->id,
                'actor_name' => $admin->name,
                'action' => 'Changed user access',
                'entity' => 'User · Dr. Marcus Lee',
                'change_summary' => 'Active → Inactive',
                'created_at' => $now->subDay(),
            ]);
        }
    }
}
