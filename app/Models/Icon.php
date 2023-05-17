<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'event_category',

        'icon',
        'icon_color',
        'icon_bg_color',
        'title',
        'title_color',
        'title_bg_color',

        'sequence',
        'hidden',
    ];
}
