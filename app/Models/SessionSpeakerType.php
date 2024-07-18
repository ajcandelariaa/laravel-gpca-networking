<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionSpeakerType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_id',
        
        'name',
        'description',

        'text_color',
        'background_color',
        
        'datetime_added',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function session()
    {
        return $this->belongsTo(Session::class, 'session_id');
    }
}
