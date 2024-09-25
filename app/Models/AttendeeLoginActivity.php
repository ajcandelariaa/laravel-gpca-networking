<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeLoginActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'logged_in_datetime',
        'expires_at_datetime',
        'logged_out_datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }
}
