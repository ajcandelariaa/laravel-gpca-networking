<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleConversationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'single_conversation_id',
        'attendee_id',
        'message',
        'file_media_id',
        'is_seen',
    ];

    public function singleConversation()
    {
        return $this->belongsTo(SingleConversation::class, 'conversation_id');
    }

    public function attendee()
    {
        return $this->belongsTo(Attendee::class, 'attendee_id');
    }

    public function file()
    {
        return $this->belongsTo(Media::class, 'file_media_id');
    }

}
