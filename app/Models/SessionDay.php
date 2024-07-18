<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionDay extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_day',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
