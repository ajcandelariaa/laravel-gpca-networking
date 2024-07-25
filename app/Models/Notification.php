<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',

        'type',
        'title',
        'subtitle',
        'message',
        'send_datetime',

        'is_sent',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
