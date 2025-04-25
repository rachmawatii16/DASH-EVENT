<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecruiterProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'company_address',
        'company_phone',
        'company_logo',
        'company_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 