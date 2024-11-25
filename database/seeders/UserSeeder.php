<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{ User, OtpMessage, Course, Section, Year };
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'first_name' => 'System',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'system_admin',
            'contact' => '09453917972'
        ]);

        OtpMessage::create([
            'email' => 'admin@gmail.com',
            'code' => '4431'
        ]);

        User::create([
            'first_name' => 'School',
            'last_name' => 'Staff',
            'email' => 'staff@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'school_staff',
            'contact' => '09453917972'
        ]);

        OtpMessage::create([
            'email' => 'staff@gmail.com',
            'code' => '4432'
        ]);

        User::create([
            'first_name' => 'School',
            'last_name' => 'Alumni',
            'email' => 'alumni@gmail.com',
            'password' => Hash::make('password'),
            'user_type' => 'school_alumni',
            'contact' => '09453917972'
        ]);

        OtpMessage::create([
            'email' => 'alumni@gmail.com',
            'code' => '4433'
        ]);

        Course::create([
            'name' => "Bachelor of Science in Information Technology (BSIT)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);


        Course::create([
            'name' => "Bachelor of Science in Computer Engineering (BSCpE)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);

        Course::create([
            'name' => "Bachelor of Science in Tourism Management (BSTM)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);

        Course::create([
            'name' => "Bachelor of Science in Business Administration (BSBA)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);

        Course::create([
            'name' => "Bachelor of Science in Technology Livehood Education (BTLE)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);

        Course::create([
            'name' => "Bachelor of Science in Hospitality Management (BSHM)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);

        Course::create([
            'name' => "Bachelor of Science in Information Technology (BSIT)",
            'batches' => "2020, 2021, 2022, 2023, 2024, 2025"
        ]);


        Section::create([
            'name' => "1"
        ]);

        Section::create([
            'name' => "2"
        ]);

        Section::create([
            'name' => "3"
        ]);

        Section::create([
            'name' => "4"
        ]);

        Section::create([
            'name' => "5"
        ]);

        Year::create([
            'name' => "2020"
        ]);

        Year::create([
            'name' => "2021"
        ]);

        Year::create([
            'name' => "2022"
        ]);

        Year::create([
            'name' => "2023"
        ]);

        Year::create([
            'name' => "2024"
        ]);

        Year::create([
            'name' => "2025"
        ]);


    }
}
