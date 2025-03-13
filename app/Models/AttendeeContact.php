<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeContact extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'contact_attendee_id',
        'datetime_added',
    ];

    public $timestamps = false;

    public function contactAttendee()
    {
        return $this->belongsTo(Attendee::class, 'contact_attendee_id');
    }
}
