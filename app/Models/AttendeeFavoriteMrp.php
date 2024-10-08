<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteMrp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'meeting_room_partner_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function meetingRoomPartner()
    {
        return $this->belongsTo(MeetingRoomPartner::class, 'meeting_room_partner_id');
    }
}
