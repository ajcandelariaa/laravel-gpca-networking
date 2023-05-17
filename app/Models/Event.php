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
        'start_date',
        'end_date',

        'splash_screen',
        'event_logo',
        'event_logo_inverted',
        'event_banner',
        'app_sponsor_logo',
        'app_sponsor_banner',

        'color_primary',
        'color_secondary',

        'year',
        'active',
    ];
}
