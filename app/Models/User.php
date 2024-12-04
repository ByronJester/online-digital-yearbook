<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'user_type',
        'contact',
        'school_id_no',
        'class_batch',
        'section',
        'program',
        'password_text',
        'position',
        'alumni_picture',
        'payment'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'role', 'fullname', 'status', 'fullname_format',
    ];

    public function getRoleAttribute()
    {
        $string = str_replace('_', ' ', $this->user_type);

        // Convert to title case (capitalize the first letter of each word)
        return ucwords($string);
    }

    public function getFullnameAttribute()
    {
        if($this->middle_name) {
            return $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name;
        }

        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullnameFormatAttribute()
    {
        if($this->middle_name) {
            return $this->last_name . ', ' . $this->first_name . ' ' . $this->middle_name;
        }

        return $this->last_name . ', ' . $this->first_name;
    }

    public function getProfilePictureAttribute($value)
    {
        if(!$value) return $value;
        // return \LaravelCloudinary::show($value, []);
        return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;
    }

    public function getAlumniPictureAttribute($value)
    {
        if(!$value) return $value;

        if (Str::contains($value, 'http')) {
            $imageData = base64_encode(file_get_contents($value));

            return [
                'image' => 'data:image/jpeg;base64,' . $imageData
            ];
        } else {
            // return "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value;

            return [
                'image' => "http://res.cloudinary.com/dcmgsini6/image/upload/" . $value
            ];
        }
    }

    public function getLastLoggedInAttribute($value)
    {
        $date = Carbon::parse($value);

        return $date->isoFormat('LLL');
    }

    public function getStatusAttribute()
    {
        if($this->last_logged_in_at && !$this->logout_at) return 'active';

        if(!!$this->logout_at) return 'in_active';

        return '';
    }

    public function batch_student()
    {
        return $this->hasOne(BatchStudent::class);
    }
}
