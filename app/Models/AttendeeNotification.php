<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'notification_id',

        'type',
        'title',
        'subtitle',
        'message',
        'sent_datetime',

        'is_seen',
        'seen_datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}
