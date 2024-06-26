<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Attendee extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'event_id',

        'badge_number',
        'registration_type',

        'pass_type',
        'company_name',
        'company_country',
        'company_phone_number',

        'username',
        'password',

        'salutation',
        'first_name',
        'middle_name',
        'last_name',
        'job_title',

        'email_address',
        'mobile_number',

        'pfp_media_id',
        'biography',

        'gender',
        'birthdate',
        'country',
        'city',
        'address',
        'nationality',

        'interests',

        'website',
        'facebook',
        'linkedin',
        'twitter',
        'instagram',

        'is_active',
        'joined_date_time',
    ];

    protected $hidden = [
        'password',
    ];
}
