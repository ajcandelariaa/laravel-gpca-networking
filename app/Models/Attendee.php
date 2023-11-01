<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',

        'username',
        'password',

        'company_name',
        'job_title',
        'country',

        'salutation',
        'first_name',
        'middle_name',
        'last_name',

        'email_address',
        'mobile_number',
        'landline_number',

        'website',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',
        
        'pfp',
        'biography',

        'badge_number',
        'pass_type',
        'registration_type',

        'active',
        'joined_date_time',
    ];
}
