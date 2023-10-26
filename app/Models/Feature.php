<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'tagline',
        'location',
        'short_description',
        'long_description',
        'link',
        'start_date',
        'end_date',
        'logo',
        'banner',
        'image',
        'active',
        'datetime_added',
    ];
}
