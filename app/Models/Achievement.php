<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Achievement extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'image', 'video', 'user_id', 'share_user_names'
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
        return "http://res.cloudinary.com/dcmgsini6/video/upload/" . $value;
    }

    public function getShareUserNamesAttribute($value)
    {
        $response = [];

        if(!$value) return [];

        $names = json_decode($value);

        if(count($names) > 0) {
            foreach($names as $name) {
                array_push($response, $name);
            }
        }

        return $response;
    }

    public function getCreatedAtAttribute($value)
    {
        $date = Carbon::parse($value)->setTimezone(config('app.timezone'));

        return $date->isoFormat('LL');
    }



}
