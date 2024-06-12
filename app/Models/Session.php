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

        'is_active',
        'datetime_added',
    ];
}
