<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AchievementComment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'achievement_id', 'user_id', 'comment'
    ];

    protected $appends = [
        'commentor'
    ];

    public function getCommentorAttribute()
    {
        $user = User::where('id', $this->user_id)->first();

        if(!$user) return null;

        return $user->fullname;
    }
}
