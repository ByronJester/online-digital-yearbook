<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'batches'
    ];

    protected $appends = [
        'batches_array'
    ];

    public function getBatchesArrayAttribute()
    {
        return explode(",", $this->batches);
    }
}
