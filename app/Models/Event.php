<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'name',
        'location',
        'description',
        'event_full_link',
        'event_short_link',
        'event_start_date',
        'event_end_date',

        'event_logo',
        'event_logo_inverted',
        'app_sponsor_logo',
        
        'event_splash_screen',
        'event_banner',
        'app_sponsor_banner',

        'year',
        'active',
    ];
}
