<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'icon',
        'title',
        'sequence',
        'hidden',
        'deletable',
        'default_icon',
    ];
}
