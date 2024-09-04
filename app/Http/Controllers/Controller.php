<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function sendSMS($number, $message)
    {
        $apiKey = '26c01596d47766a3a5728182d39bca05';

        $ch = curl_init();

        $parameters = array(
            'apikey' =>  $apiKey,
            'number' => $number,
            'message' => $message,
            'sendername' => 'SEMAPHORE'
        );

        curl_setopt( $ch, CURLOPT_URL,'https://semaphore.co/api/v4/messages' );
        curl_setopt( $ch, CURLOPT_POST, 1 );

        //Send the parameters set above with the request
        curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $parameters ) );

        // Receive response from server
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        $output = curl_exec( $ch );
        curl_close ($ch);

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/priority');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/x-www-form-urlencoded',
        // ]);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=$apiKey&number=$number&message=$message");

        // $response = curl_exec($ch);

        // curl_close($ch);

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://semaphore.co/api/v4/otp');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        // curl_setopt($ch, CURLOPT_HTTPHEADER, [
        //     'Content-Type: application/x-www-form-urlencoded',
        // ]);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "apikey=$apiKey&number=$number&message=$message");

        // $response = curl_exec($ch);

        // curl_close($ch);
    }
}
