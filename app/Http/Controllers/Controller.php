<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
// use Cloudinary\Api\Upload\UploadApi;
// use Cloudinary\Configuration\Configuration;
// use Cloudinary\Cloudinary;
// use Cloudinary\Laravel\Facades\Cloudinary as CloudinaryFacade;
// use Cloudinary\CloudinaryBuilder;
use Cloudinary\Cloudinary;

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
    }

    public function uploadFile($file, $identifier)
    {
        $options = [

        ];

        $tags = [

        ];

        \LaravelCloudinary::upload($file, $identifier, $options, $tags);

        return $identifier;

    }
}
