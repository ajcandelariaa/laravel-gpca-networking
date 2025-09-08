<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeOtpCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'purpose',
        'code_hash',
        'expires_datetime',
        'is_used',
        'used_datetime',
        'attempts',
    ];

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }
}
