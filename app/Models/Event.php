<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'recruiter_id', 'date', 'time', 'location', 'price', 'status'];

    public function recruiter()
    {
        return $this->belongsTo(User::class, 'recruiter_id');
    }

    public function applicants()
    {
        // return $this->belongsToMany(User::class, 'event_applicant', 'event_id', 'applicant_id');
        return $this->belongsToMany(User::class, 'event_applicant', 'event_id', 'applicant_id')
                    ->withPivot('payment_proof', 'status_pembayaran')
                    ->withTimestamps();
    }
}
