<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',

        'full_name',
        'short_name',
        'edition',

        'location',
        'description_html_text',

        'link',
        'start_date',
        'end_date',

        'logo_media_id',
        'banner_media_id',

        'is_active',
        'datetime_added',
    ];
}
