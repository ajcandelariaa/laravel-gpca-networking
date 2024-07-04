<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendeeFavoriteMp extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'media_partner_id',
    ];

    public function mediaPartner()
    {
        return $this->belongsTo(MediaPartner::class, 'media_partner_id');
    }
}
