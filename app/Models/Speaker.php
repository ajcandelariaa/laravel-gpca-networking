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

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

    public function speakerType()
    {
        return $this->belongsTo(SpeakerType::class, 'speaker_type_id');
    }

    public function pfp()
    {
        return $this->belongsTo(Media::class, 'pfp_media_id');
    }

    public function coverPhoto()
    {
        return $this->belongsTo(Media::class, 'cover_photo_media_id');
    }
}
