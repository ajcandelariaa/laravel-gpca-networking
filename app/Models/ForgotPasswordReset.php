<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ForgotPasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendee_id',
        'email_address',
        'otp',
        'expires_at',
        'is_used',
        'is_password_changed',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }
}
