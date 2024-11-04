<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Greeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'files'
    ];

    public function getFilesAttribute($value)
    {
        $response = [];

        $files = json_decode($value);

        if(count($files) > 0) {
            foreach($files as $file) {
                // array_push($response, "http://res.cloudinary.com/dcmgsini6/image/upload/" . $image);
                if (Str::contains($file, 'video')) {
                    array_push($response, "http://res.cloudinary.com/dcmgsini6/video/upload/" . $file);
                } else {
                    array_push($response, "http://res.cloudinary.com/dcmgsini6/image/upload/" . $file);
                }
            }
        }

        return $response;
    }
}
