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
}
