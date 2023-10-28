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
        'profile',
        'link',
        'email_address',
        'mobile_number',
        'logo',
        'banner',
        'active',
        'datetime_added',
    ];
}
