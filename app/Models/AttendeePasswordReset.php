<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeePasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'password_changed_by',
        'password_changed_date_time',
    ];
}
