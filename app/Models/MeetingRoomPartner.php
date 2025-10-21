<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MeetingRoomPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',

        'name',
        'location',
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

        'floorplan_link',

        'is_active',
        'datetime_added',
    ];
    
    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_media_id');
    }

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_media_id');
    }
}
