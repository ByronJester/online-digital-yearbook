<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserShare extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'shared_id', 'type'
    ];

    protected $appends = [
        'shared_content'
    ];

    public function getSharedContentAttribute()
    {
        // return $this->shared_id;
        if($this->type == 'album') {
            return Album::with(['likes', 'comments', 'user'])->where('id', $this->shared_id)->first();
        } else {
            return Achievement::with(['likes', 'comments', 'user'])->where('id', $this->shared_id)->first();
        }
    }
}
