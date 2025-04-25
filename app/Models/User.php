<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'profile_photo', 'company_name'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_applicant', 'applicant_id', 'event_id');
    }

    // public function joinedWebinars()
    // {
    //     return $this->belongsToMany(Webinar::class, 'webinar_applicant', 'applicant_id', 'webinar_id');
    // }

    public function education()
    {
        return $this->hasMany(Education::class);
    }

    public function experience()
    {
        return $this->hasMany(Experience::class);
    }

    // public function applications()
    // {
    //     return $this->hasMany(Application::class);
    // }

}
