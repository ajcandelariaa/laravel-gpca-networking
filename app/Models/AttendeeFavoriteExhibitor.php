<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteExhibitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'exhibitor_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function exhibitor()
    {
        return $this->belongsTo(Exhibitor::class, 'exhibitor_id');
    }
}
