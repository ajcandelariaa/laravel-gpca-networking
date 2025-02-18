<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'feature_id',

        'session_date',
        'session_day',
        'session_type',

        'title',
        'description_html_text',
        'start_time',
        'end_time',
        'location',

        'sponsor_id',

        'is_active',
        'datetime_added',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function sponsor()
    {
        return $this->belongsTo(Sponsor::class, 'sponsor_id');
    }
}
