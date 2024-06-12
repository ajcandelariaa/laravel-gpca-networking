<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_url',
        'file_directory',
        'file_name',
        'file_type',
        'file_size',
        'width',
        'height',
        'date_uploaded',
    ];
}
