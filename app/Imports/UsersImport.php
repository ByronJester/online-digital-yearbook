<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UsersImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            $userType = $row['user_type'];
            $password = Str::random(8);

            if ($userType === 'school_staff') {
                User::updateOrCreate(
                    ['email' => $row['email']],
                    [
                        'first_name' => $row['first_name'],
                        'middle_name' => $row['middle_name'],
                        'last_name' => $row['last_name'],
                        'email' => $row['email'],
                        'contact' => $row['contact'],
                        'user_type' => $row['user_type'],
                        'position' => $row['position'],
                        'password' => Hash::make($password),
                        'password_text' => $password
                    ]
                );
            } elseif ($userType === 'school_alumni') {
                User::updateOrCreate(
                    ['email' => $row['email_address']],
                    [
                        'first_name' => $row['first_name'],
                        'middle_name' => $row['middle_name'],
                        'last_name' => $row['last_name'],
                        'school_id_no' => $row['school_id_number'],
                        'email' => $row['email_address'],
                        'contact' => $row['contact'],
                        'user_type' => $row['user_type'],
                        'class_batch' => $row['class_batch'],
                        'program' => $row['program'],
                        'section' => $row['section'],
                        'password' => Hash::make($password),
                        'password_text' => $password
                    ]
                );
            }
        }
    }
}
