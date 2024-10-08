<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SessionDate extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'session_date',
        'description',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
