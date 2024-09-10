<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logo extends Model
{
    use HasFactory;

    protected $fillable = [
        'file'
    ];


    public function getFileAttribute($value)
    {
        // return \LaravelCloudinary::show($value, []);
        return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;
    }
}
