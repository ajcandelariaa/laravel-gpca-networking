<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'receiver_id',
        'receiver_type',
        'meeting_status',
        'meeting_title',
        'meeting_date',
        'meeting_start_time',
        'meeting_end_time',
        'meeting_location',
        'meeting_notes',
        'accepted_datetime',
        'declined_datetime',
        'cancelled_datetime',
        'accepted_reason',
        'declined_reason',
        'cancelled_reason',
        'is_reschedule',
        'parent_meeting_id',

        'respond_token',
        'respond_token_expires_at',
        'respond_token_status',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function requester()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function parentMeeting()
    {
        return $this->belongsTo(AttendeeMeeting::class, 'parent_meeting_id');
    }
}
