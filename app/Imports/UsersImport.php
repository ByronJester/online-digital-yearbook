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
                $exist = User::where('email', $row['email_address'])->first();
                if($exist) {
                    User::updateOrCreate(
                        ['email' => $row['email']],
                        [
                            'first_name' => $row['first_name'],
                            'middle_name' => $row['middle_name'],
                            'last_name' => $row['last_name'],
                            'email' => $row['email'],
                            'contact' => $row['contact'],
                            'user_type' => $row['user_type'],
                            'position' => $row['position']
                        ]
                    );
                } else {
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

                    $email = $row['email'];
                    $message = "Your email is $email and your password is $password. You can login now using this credentials.";

                    $this->sendSMS($row['contact'], $message);
                }

            } elseif ($userType === 'school_alumni') {
                $exist = User::where('email', $row['email_address'])->first();

                if($exist) {
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
                            'section' => $row['section']
                        ]
                    );
                } else {
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

                    $email = $row['email_address'];
                    $message = "Your email is $email and your password is $password. You can login now using this credentials.";

                    $this->sendSMS($row['contact'], $message);
                }



            }


        }
    }

    public function sendSMS($number, $message)
    {
        $apiKey = '26c01596d47766a3a5728182d39bca05';

        // $parameters = array(
        //     'apikey' =>  $apiKey,
        //     'number' => $number,
        //     'message' => $message,
        //     'sendername' => 'ODY'
        // );

        // curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages' );
        // curl_setopt( $ch, CURLOPT_POST, 1 );

        // curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

        // curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        // $output = curl_exec( $ch );
        // curl_close ($ch);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/priority');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=$apiKey&number=$number&message=$message");

        $response = curl_exec($ch);

        curl_close($ch);
    }
}
