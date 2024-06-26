<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sponsor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'feature_id',
        'sponsor_type_id',

        'name',
        'profile_html_text',
        'logo_media_id',
        'banner_media_id',

        'country',
        'contact_person_name',
        'email_address',
        'mobile_number',
        
        'website',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',

        'is_active',
        'datetime_added',
    ];
}
