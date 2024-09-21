<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'image', 'video', 'user_id'
    ];

    public function likes()
    {
        return $this->hasMany(AchievementLike::class, 'achievement_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(AchievementComment::class, 'achievement_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageAttribute($value)
    {
        if(!$value) return $value;
        // return \LaravelCloudinary::show($value, []);
        return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;
    }

    public function getVideoAttribute($value)
    {
        if(!$value) return $value;
        // return \LaravelCloudinary::show($value, []);
        return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;
    }

}
