<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Session as Sessions;
use App\Models\Feature as Features;
use Carbon\Carbon;

class SessionDetails extends Component
{
    public $event, $sessionData;

    public $editSessionDetailsForm, $category, $session_date, $session_day, $session_type, $title, $description, $start_time, $end_time, $location, $categoryChoices = array();
    
    protected $listeners = ['editSessionDetailsConfirmed' => 'editSessionDetails'];

    public function mount($eventId, $eventCategory, $sessionData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->sessionData = $sessionData;
        $this->editSessionDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.sessions.session-details');
    }

    
}
