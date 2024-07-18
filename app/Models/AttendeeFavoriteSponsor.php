<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteSponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'sponsor_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }
}
