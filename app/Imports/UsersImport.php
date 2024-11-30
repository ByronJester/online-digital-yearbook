<?php

namespace App\Imports;

use App\Models\{User, Batch, BatchStudent};
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
                $exist = User::where('email', $row['email'])->first();
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

                $user = null;

                // $imageUpload = null;

                // if ($request->hasFile('alumni_image')) {
                //     $alumniImage = Str::random(10) . '_alumni_image';
                //     $imageUpload = $this->uploadFile($request->file('alumni_image'), $alumniImage);
                // }

                if($exist) {
                    $user = User::updateOrCreate(
                        ['email' => $row['email_address']],
                        [
                            'first_name' => $row['first_name'],
                            'middle_name' => $row['middle_name'],
                            'last_name' => $row['last_name'],
                            'school_id_no' => $row['alumni_id_number'],
                            'email' => $row['email_address'],
                            'contact' => $row['contact'],
                            'user_type' => $row['user_type'],
                            'class_batch' => $row['class_batch'],
                            'program' => $row['program'],
                            'section' => $row['section'],
                            'alumni_picture' => $row['alumni_image'],
                            'payment' => $row['payment']
                        ]
                    );
                } else {
                   $user = User::updateOrCreate(
                        ['email' => $row['email_address']],
                        [
                            'first_name' => $row['first_name'],
                            'middle_name' => $row['middle_name'],
                            'last_name' => $row['last_name'],
                            'school_id_no' => $row['alumni_id_number'],
                            'email' => $row['email_address'],
                            'contact' => $row['contact'],
                            'user_type' => $row['user_type'],
                            'class_batch' => $row['class_batch'],
                            'program' => $row['program'],
                            'section' => $row['section'],
                            'password' => Hash::make($password),
                            'password_text' => $password,
                            'alumni_picture' => $row['alumni_image'],
                            'payment' => $row['payment']
                        ]
                    );

                    if($row['payment'] == 'paid') {
                        $email = $row['email_address'];
                        $message = "Your email is $email and your password is $password. You can login now using this credentials.";

                        $this->sendSMS($row['contact'], $message);
                    }


                }

                $batch = Batch::updateOrCreate(
                    ['school_year' => $row['class_batch'], 'course' => $row['program'], 'section' => $row['section']],
                    ['school_year' => $row['class_batch'], 'course' => $row['program'], 'section' => $row['section']]
                );

                BatchStudent::updateOrCreate(
                    ['batch_id' => $batch->id, 'user_id' => $user->id],
                    ['batch_id' => $batch->id, 'user_id' => $user->id, 'award' => $row['award']]
                );



            }


        }
    }

    public function sendSMS($number, $message)
    {
        $apiKey = base64_decode('MjZjMDE1OTZkNDc3NjZhM2E1NzI4MTgyZDM5YmNhMDU=');

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
