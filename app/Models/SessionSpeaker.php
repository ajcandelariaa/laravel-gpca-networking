<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSpeaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_id',
        'session_speaker_type_id',
        'speaker_id',
    ];
}
