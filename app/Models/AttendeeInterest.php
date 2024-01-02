<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeInterest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',

        'technology',
        'innovation',
        'leadership',
        'sustainability',
        'startups',
        'digital_transformation',
        'ceo',
        'developer',
        'designer',
        'marketing_commercial',
        'engineer',
        'ehss',
        'procurement',
        'human_resources',
        'consultants',
        'academia',
        'investors',
        'service_providers',
    ];
}
