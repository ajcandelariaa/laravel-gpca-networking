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

        'salutation',
        'first_name',
        'middle_name',
        'last_name',
        'email_address',
        'mobile_number',
        'landline_number',

        'company_name',
        'job_title',
        'country',
        
        'image',
        'biography',

        'badge_number',
        'pass_type',
        'registration_type',

        'joined_date_time',
        
        'password_changed_date_time',
        'password_changed_count',
    ];
}
