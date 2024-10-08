<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Session as Sessions;
use App\Models\SessionSpeakerType as SessionSpeakerTypes;
use Carbon\Carbon;
use Livewire\Component;

class SessionSpeakerTypeList extends Component
{
    public $event, $session;

    public $finalListOfSpeakerTypes = array(), $finalListOfSpeakerTypesConst = array();

    public $name, $description, $text_color, $background_color, $editState, $editId, $editArrayIndex;
    
    // EDIT DATE TIME
    public $speakerTypeId, $speakerTypeDateTime, $speakerTypeArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editSpeakerTypeDateTimeForm;

    protected $listeners = ['addSpeakerTypeConfirmed' => 'addSpeakerType'];

    public function mount($eventId, $eventCategory, $sessionId)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->session = Sessions::where('event_id', $eventId)->where('id', $sessionId)->first();

        $speakersType = SessionSpeakerTypes::where('event_id', $eventId)->where('session_id', $sessionId)->orderBy('datetime_added', 'ASC')->get();

        if ($speakersType->isNotEmpty()) {
            foreach ($speakersType as $type) {
                array_push($this->finalListOfSpeakerTypes, [
                    'id' => $type->id,
                    'name' => $type->name,
                    'description' => $type->description,
                    'text_color' => $type->text_color,
                    'background_color' => $type->background_color,
                    'datetime_added' => Carbon::parse($type->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSpeakerTypesConst = $this->finalListOfSpeakerTypes;
        }

        $this->inputNameVariableDateTime = "speakerTypeDateTime";
        $this->btnUpdateNameMethodDateTime = "editSpeakerTypeDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditSpeakerTypeDateTimeFields";

        $this->editSpeakerTypeDateTimeForm = false;
        $this->editState = false;
    }

    public function render()
    {
        return view('livewire.event.sessions.speakers.session-speaker-type-list');
    }

    public function addSpeakerTypeConfirmation(){
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSpeakerTypeConfirmed",
        ]);
    }

    public function addSpeakerType(){
        $newSpeakerType = SessionSpeakerTypes::create([
            'event_id' => $this->event->id,
            'session_id' => $this->session->id,
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
            'datetime_added' => Carbon::now(),
        ]);

        array_push($this->finalListOfSpeakerTypes, [
            'id' => $newSpeakerType->id,
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        
        $this->finalListOfSpeakerTypesConst = $this->finalListOfSpeakerTypes;
        
        $this->name = null;
        $this->text_color = null;
        $this->background_color = null;

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker type added successfully!',
            'text' => ''
        ]);
    }


    // EDIT PART
    public function showEditForm($arrayIndex){
        $this->name = $this->finalListOfSpeakerTypes[$arrayIndex]['name'];
        $this->description = $this->finalListOfSpeakerTypes[$arrayIndex]['description'];
        $this->text_color = $this->finalListOfSpeakerTypes[$arrayIndex]['text_color'];
        $this->background_color = $this->finalListOfSpeakerTypes[$arrayIndex]['background_color'];
        $this->editArrayIndex = $arrayIndex;
        $this->editId = $this->finalListOfSpeakerTypes[$arrayIndex]['id'];
        $this->editState = true;
    }

    public function cancelEditSpeakerType(){
        $this->resetEditSpeakerTypeFields();
    }

    public function resetEditSpeakerTypeFields(){
        $this->editState = false;
        $this->name = null;
        $this->description = null;
        $this->text_color = null;
        $this->background_color = null;
        $this->editArrayIndex = null;
        $this->editId = null;
    }

    public function editSpeakerType()
    {
        $this->validate([
            'name' => 'required',
        ]);

        SessionSpeakerTypes::where('id', $this->editId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
        ]);

        $this->finalListOfSpeakerTypes[$this->editArrayIndex]['name'] = $this->name;
        $this->finalListOfSpeakerTypes[$this->editArrayIndex]['description'] = $this->description;
        $this->finalListOfSpeakerTypes[$this->editArrayIndex]['text_color'] = $this->text_color;
        $this->finalListOfSpeakerTypes[$this->editArrayIndex]['background_color'] = $this->background_color;

        $this->resetEditSpeakerTypeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker type updated successfully!',
            'text' => ''
        ]);
    }



    // EDIT DATETIME
    public function showEditSpeakerTypeDateTime($speakerTypeId, $speakerTypeArrayIndex)
    {
        $speakerTypeDateTime = SessionSpeakerTypes::where('id', $speakerTypeId)->value('datetime_added');

        $this->speakerTypeId = $speakerTypeId;
        $this->speakerTypeDateTime = $speakerTypeDateTime;
        $this->speakerTypeArrayIndex = $speakerTypeArrayIndex;
        $this->editSpeakerTypeDateTimeForm = true;
    }

    public function resetEditSpeakerTypeDateTimeFields()
    {
        $this->editSpeakerTypeDateTimeForm = false;
        $this->speakerTypeId = null;
        $this->speakerTypeDateTime = null;
        $this->speakerTypeArrayIndex = null;
    }
    
    public function editSpeakerTypeDateTime()
    {
        $this->validate([
            'speakerTypeDateTime' => 'required',
        ]);

        SessionSpeakerTypes::where('id', $this->speakerTypeId)->update([
            'datetime_added' => $this->speakerTypeDateTime,
        ]);

        $this->finalListOfSpeakerTypes[$this->speakerTypeArrayIndex]['datetime_added'] = Carbon::parse($this->speakerTypeDateTime)->format('M j, Y g:i A');

        $this->resetEditSpeakerTypeDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker type Datetime updated successfully!',
            'text' => ''
        ]);
    }
}
