<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Speaker extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',

        'salutation',
        'first_name',
        'middle_name',
        'last_name',

        'company_name',
        'job_title',

        'biography',

        'pfp',
        'cover_photo',

        'active',
        
        'datetime_added',
    ];
}
