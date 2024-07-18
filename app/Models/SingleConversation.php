<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SingleConversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'created_by_attendee_id',
        'recipient_attendee_id',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Attendee::class, 'created_by_attendee_id');
    }

    public function recipient()
    {
        return $this->belongsTo(Attendee::class, 'recipient_attendee_id');
    }

    public function messages()
    {
        return $this->hasMany(SingleConversationMessage::class, 'single_conversation_id');
    }
}
