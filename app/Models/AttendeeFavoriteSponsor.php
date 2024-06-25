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
}
