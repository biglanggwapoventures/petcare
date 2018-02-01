<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class DoctorProfile extends Model
{
    protected $fillable = [
        'doctor_id',
        'photo_filepath',
        'specialty',
        'schedule',
        'bio',
    ];

    protected $casts = [
        'schedule' => 'array',
    ];

    public function getPhotoFilepathAttribute($val)
    {
        return $val ? asset("storage/{$val}") : null;
    }

    public function account()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
