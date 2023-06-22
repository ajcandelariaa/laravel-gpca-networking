<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Attendee as AttendeesModel;
use Livewire\Component;

class Attendees extends Component
{
    public $event, $attendees;

    public function mount($eventId, $eventCategory){
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->attendees = AttendeesModel::where('event_id', $eventId)->get();
    }

    public function render()
    {
        return view('livewire.event.attendees.attendees');
    }
}
