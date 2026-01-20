<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Users\Entities\User;
use Modules\Doctors\Entities\Doctor;
use Modules\Doctors\Entities\DoctorSchedule;
use Modules\Patients\Entities\Patient;
use Modules\Specialties\Entities\Category;
use Modules\Users\Entities\Governorate;
use Modules\Users\Entities\City;

class TestUsersSeeder extends Seeder
{
    /**
     * Seed the test users for development/testing.
     * 
     * Creates one user of each role:
     * - Admin: admin@clinic.com / password
     * - Doctor: doctor@clinic.com / password  
     * - Patient: patient@clinic.com / password
     */
    public function run(): void
    {
        // Get reference data
        $governorate = Governorate::first();
        $city = City::first();
        $category = Category::first();

        $this->command->info('Creating test users...');

        // Create Doctor User
        $doctorUser = User::firstOrCreate(
            ['email' => 'doctor@clinic.com'],
            [
                'name' => 'د. محمد أحمد',
                'phone_number' => '01012345678',
                'password' => Hash::make('password'),
                'status' => true,
                'governorate_id' => $governorate?->id,
                'city_id' => $city?->id,
            ]
        );

        if (!$doctorUser->hasRole('Doctor')) {
            $doctorUser->assignRole('Doctor');
        }

        // Create Doctor profile
        $doctor = Doctor::firstOrCreate(
            ['user_id' => $doctorUser->id],
            [
                'name' => 'د. محمد أحمد',
                'description' => 'طبيب متخصص في الطب الباطني مع خبرة 10 سنوات',
                'governorate_id' => $governorate?->id,
                'city_id' => $city?->id,
                'category_id' => $category?->id,
                'address' => 'شارع النصر، المنصورة',
                'degree' => 'دكتوراه',
                'waiting_time' => 15,
                'consultation_fee' => 200,
                'experience_years' => 10,
                'gender' => 'male',
                'status' => true,
                'title' => 'استشاري',
                'is_profile_completed' => true,
                'rating_avg' => 4.5
            ]
        );

        // Create doctor schedules (Sunday to Thursday)
        $days = ['sunday', 'monday', 'tuesday', 'wednesday', 'thursday'];
        foreach ($days as $day) {
            DoctorSchedule::firstOrCreate(
                ['doctor_id' => $doctor->id, 'day' => $day],
                [
                    'start_time' => '09:00',
                    'end_time' => '17:00',
                    'is_active' => true
                ]
            );
        }

        $this->command->info("✓ Doctor created: doctor@clinic.com / password");

        // Create Patient User
        $patientUser = User::firstOrCreate(
            ['email' => 'patient@clinic.com'],
            [
                'name' => 'أحمد علي',
                'phone_number' => '01098765432',
                'password' => Hash::make('password'),
                'status' => true,
                'governorate_id' => $governorate?->id,
                'city_id' => $city?->id,
            ]
        );

        if (!$patientUser->hasRole('Patient')) {
            $patientUser->assignRole('Patient');
        }

        // Create Patient profile
        Patient::firstOrCreate(
            ['user_id' => $patientUser->id],
            [
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'address' => 'شارع الجمهورية، القاهرة',
                'medical_history' => 'لا يوجد تاريخ مرضي مهم',
                'emergency_contact' => '01011111111',
                'blood_type' => 'A+',
                'allergies' => 'لا يوجد',
                'status' => true
            ]
        );

        $this->command->info("✓ Patient created: patient@clinic.com / password");

        $this->command->info('');
        $this->command->info('=== Test Credentials ===');
        $this->command->info('Admin:   admin@clinic.com / password');
        $this->command->info('Doctor:  doctor@clinic.com / password');
        $this->command->info('Patient: patient@clinic.com / password');
    }
}
