<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaPartner extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'profile',
        'logo',
        'banner',

        'country',
        'contact_person_name',
        'email_address',
        'mobile_number',
        'website',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',

        'active',
        'datetime_added',
    ];
}
