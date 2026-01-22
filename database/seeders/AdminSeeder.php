<?php

namespace Database\Seeders;

use Modules\Users\Entities\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@clinic.com'],
            [
                'name' => 'Admin',
                'phone_number' => '01020114717',
                'password' => Hash::make('password'),
                'status' => true
            ]
        );
        $admin->assignRole('Admin');

        $secondAdmin = User::firstOrCreate(
            ['email' => 'abdallahassaker@gmail.com'],
            [
                'name' => 'Abdallah Assaker',
                'phone_number' => '01020114717',
                'password' => Hash::make('01020114717'),
                'status' => true
            ]
        );
        $secondAdmin->assignRole('Admin');
    }
}
