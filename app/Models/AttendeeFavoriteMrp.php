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
}
