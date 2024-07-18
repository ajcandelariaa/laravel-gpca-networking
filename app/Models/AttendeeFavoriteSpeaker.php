<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'speaker_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }
}
