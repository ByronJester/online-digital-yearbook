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

        // $ch = curl_init();

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

    public function uploadFile($file, $identifier)
    {
        // Determine the file extension
        $fileExtension = strtolower($file->getClientOriginalExtension());

        // Initialize options and tags arrays
        $options = [];
        $tags = [];

        // Check if the file is a video or image and set the appropriate resource type
        if (in_array($fileExtension, ['mp4', 'avi', 'mov', 'mkv', 'webm'])) {
            // Video upload
            $options['resource_type'] = 'video';
        } else {
            // Default to image upload
            $options['resource_type'] = 'image';
        }

        // Perform the upload to Cloudinary
        try {
            $result = \LaravelCloudinary::upload($file->getRealPath(), $identifier, $options, $tags);

            return $identifier;
        } catch (\Exception $e) {
            // Handle any errors during the upload
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function getBadWords()
    {
        return [
            'putangina', 'tangina', 'gago', 'tanga', 'bobo', 'bulok', 'ulol', 'leche', 'bakla',
            'bading', 'tomboy', 'bansot', 'payatot', 'panget',

            'fuck', 'shit', 'asshole', 'bitch', 'dick', 'cunt', 'hell', 'bastard', 'idiot', 'moron',
            'dumbass', 'scumbag', 'prick', 'loser', 'jerk', 'slut', 'whore', 'skank', 'piss off',
            'shut up', 'asshat', 'douche', 'freak', 'fuckface', 'fuckwit', 'dirtbag', 'tool', 'pig',
            'twat', 'trash', 'scum', 'motherfucker', 'assclown', 'nitwit', 'knucklehead', 'dipshit',
            'clown', 'blockhead', 'bonehead', 'snitch', 'asswipe', 'dickwad', 'trashbag', 'wimp',
            'fool', 'jackass', 'savage', 'crap',

            // Tagalog Curse Words
            'putang ina', 'tangina', 'gago', 'tanga', 'bobo', 'ulol', 'lintik', 'bwisit', 'putragis',
            'tarantado', 'peste', 'hayop', 'luko-luko', 'anak ng puta', 'tangina mo', 'leche', 'yawa',
            'demonyo', 'hudas', 'kupal', 'hayop ka', 'ahas', 'putok sa buho', 'hinayupak', 'pakshet',
            'ugok', 'kantutero', 'buang', 'sinungaling', 'manyak', 'bakla', 'salot', 'loko', 'tarantang',
            'unggoy', 'bastos', 'inutil', 'alipin', 'gunggong', 'batugan', 'taksil', 'tamad', 'suklam',
            'bruha', 'walang hiya', 'walang kwenta', 'tarantado', 'busabos', 'pabebe', 'hayop ka'
        ];
    }
}
