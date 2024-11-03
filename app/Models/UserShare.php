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
        if($this->type == 'album') {
            return Album::where('id', $this->shared_id)->with(['likes', 'comments', 'user'])->first();
        } else {
            return Achievement::where('id', $this->shared_id)->with(['likes', 'comments', 'user'])->first();
        }
    }
}
