<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuccessStory extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name', 'content', 'file'
    ];

    public function getFileAttribute($value)
    {
        if(!$value) return $value;
        // return \LaravelCloudinary::show($value, []);
        return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;
    }

    protected $appends = [
        'alumni_name'
    ];

    public function getAlumniNameAttribute()
    {
        return $this->student_name;
    }
}
