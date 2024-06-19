<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'feature_id',
        'speaker_type_id',

        'salutation',
        'first_name',
        'middle_name',
        'last_name',

        'company_name',
        'job_title',

        'biography_html_text',

        'pfp_media_id',
        'cover_photo_media_id',

        'country',
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
