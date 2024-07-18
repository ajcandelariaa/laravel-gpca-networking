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

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }

    public function sessionSpeakerType()
    {
        return $this->belongsTo(SessionSpeakerType::class, 'session_speaker_type_id');
    }

    public function speaker()
    {
        return $this->belongsTo(Speaker::class, 'speaker_id');
    }
}
