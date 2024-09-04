<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{ User, OtpMessage };
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
            'contact' => '09771259259'
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
            'contact' => '09771259259'
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
            'contact' => '09771259259'
        ]);

        OtpMessage::create([
            'email' => 'alumni@gmail.com',
            'code' => '4433'
        ]);
    }
}
