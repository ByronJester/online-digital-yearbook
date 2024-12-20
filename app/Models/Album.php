<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Album extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'content', 'image', 'video', 'user_id', 'description', 'share_user_names', 'archive_at'
    ];


    public function likes()
    {
        return $this->hasMany(AlbumLike::class, 'album_id', 'id');
    }

    public function comments()
    {
        return $this->hasMany(AlbumComment::class, 'album_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getImageAttribute($value)
    {
        $response = [];

        $images = json_decode($value);

        if(count($images) > 0) {
            foreach($images as $image) {
                array_push($response, "http://res.cloudinary.com/dcmgsini6/image/upload/" . $image);
            }
        }

        return $response;
    }

    public function getVideoAttribute($value)
    {
        $response = [];

        $videos = json_decode($value);

        if(count($videos) > 0) {
            foreach($videos as $video) {
                array_push($response, "https://res.cloudinary.com/dcmgsini6/video/upload/" . $video);
            }
        }

        return $response;

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
