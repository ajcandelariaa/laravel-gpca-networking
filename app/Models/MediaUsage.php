<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'media_id',
        'entity_type',
        'entity_id',
    ];
}
