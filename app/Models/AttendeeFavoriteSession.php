<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'session_id',
    ];
}
