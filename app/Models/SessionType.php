<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_type',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
