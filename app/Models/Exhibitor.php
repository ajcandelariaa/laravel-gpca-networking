<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exhibitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'profile',
        'logo',
        'banner',
        'stand_number',
        'email_address',
        'mobile_number',
        'link',
        'active',
        'datetime_added',
    ];
}
