<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SelfMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'date', 'message', 'is_sent'
    ];

    protected $appends = [
        'formatted_date'
    ];

    public function getFormattedDateAttribute()
    {
        $date = Carbon::parse($this->date);

        return $date->isoFormat('LL');
    }
}
