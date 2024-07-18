<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeePasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'password_changed_by',
        'password_changed_date_time',
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
