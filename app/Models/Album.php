<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;

    protected $fillable = [
        'content', 'image', 'video', 'user_id'
    ];


    public function likes()
    {
        return $this->hasMany(AlbumLike::class);
    }

    public function comments()
    {
        return $this->hasMany(AlbumComment::class);
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
                array_push($response, "http://res.cloudinary.com/dcmgsini6/image/upload/" . $video);
            }
        }

        return $response;

    }
}
