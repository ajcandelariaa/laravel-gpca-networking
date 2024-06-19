<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\SponsorType as SponsorTypes;
use Carbon\Carbon;
use Livewire\Component;

class SponsorTypeList extends Component
{
    public $event;
    public $finalListOfSponsorTypes = array(), $finalListOfSponsorTypesConst = array();

    public $name, $description, $text_color, $background_color, $editState, $editId, $editArrayIndex;
    
    public $sponsorTypeId, $sponsorTypeDateTime, $sponsorTypeArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editSponsorTypeDateTimeForm;

    protected $listeners = ['addSponsorTypeConfirmed' => 'addSponsorType'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $sponsorsType = SponsorTypes::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($sponsorsType->isNotEmpty()) {
            foreach ($sponsorsType as $type) {
                array_push($this->finalListOfSponsorTypes, [
                    'id' => $type->id,
                    'name' => $type->name,
                    'description' => $type->description,
                    'text_color' => $type->text_color,
                    'background_color' => $type->background_color,
                    'datetime_added' => Carbon::parse($type->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSponsorTypesConst = $this->finalListOfSponsorTypes;
        }

        $this->inputNameVariableDateTime = "sponsorTypeDateTime";
        $this->btnUpdateNameMethodDateTime = "editSponsorTypeDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditSponsorTypeDateTimeFields";

        $this->editState = false;
    }

    public function render()
    {
        return view('livewire.event.sponsors.type.sponsor-type-list');
    }

    public function addSponsorTypeConfirmation(){
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSponsorTypeConfirmed",
        ]);
    }

    public function addSponsorType(){
        $newSponsorType = SponsorTypes::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
            'datetime_added' => Carbon::now(),
        ]);

        array_push($this->finalListOfSponsorTypes, [
            'id' => $newSponsorType->id,
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        
        $this->finalListOfSponsorTypesConst = $this->finalListOfSponsorTypes;
        
        $this->name = null;
        $this->text_color = null;
        $this->background_color = null;

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Sponsor type added successfully!',
            'text' => ''
        ]);
    }


    // EDIT DATETIME
    public function showEditSponsorTypeDateTime($sponsorTypeId, $sponsorTypeArrayIndex)
    {
        $sponsorTypeDateTime = SponsorTypes::where('id', $sponsorTypeId)->value('datetime_added');

        $this->sponsorTypeId = $sponsorTypeId;
        $this->sponsorTypeDateTime = $sponsorTypeDateTime;
        $this->sponsorTypeArrayIndex = $sponsorTypeArrayIndex;
        $this->editSponsorTypeDateTimeForm = true;
    }

    public function resetEditSponsorTypeDateTimeFields()
    {
        $this->editSponsorTypeDateTimeForm = false;
        $this->sponsorTypeId = null;
        $this->sponsorTypeDateTime = null;
        $this->sponsorTypeArrayIndex = null;
    }
    
    public function editSponsorTypeDateTime()
    {
        $this->validate([
            'sponsorTypeDateTime' => 'required',
        ]);

        SponsorTypes::where('id', $this->sponsorTypeId)->update([
            'datetime_added' => $this->sponsorTypeDateTime,
        ]);

        $this->finalListOfSponsorTypes[$this->sponsorTypeArrayIndex]['datetime_added'] = Carbon::parse($this->sponsorTypeDateTime)->format('M j, Y g:i A');
        $this->finalListOfSponsorTypesConst[$this->sponsorTypeArrayIndex]['datetime_added'] = Carbon::parse($this->sponsorTypeDateTime)->format('M j, Y g:i A');

        $this->resetEditSponsorTypeDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Feature Datetime updated successfully!',
            'text' => ''
        ]);
    }


    // EDIT PART
    public function showEditForm($arrayIndex){
        $this->name = $this->finalListOfSponsorTypes[$arrayIndex]['name'];
        $this->description = $this->finalListOfSponsorTypes[$arrayIndex]['description'];
        $this->text_color = $this->finalListOfSponsorTypes[$arrayIndex]['text_color'];
        $this->background_color = $this->finalListOfSponsorTypes[$arrayIndex]['background_color'];
        $this->editArrayIndex = $arrayIndex;
        $this->editId = $this->finalListOfSponsorTypes[$arrayIndex]['id'];
        $this->editState = true;
    }

    public function resetEditSponsorTypeFields(){
        $this->editState = false;
        $this->name = null;
        $this->description = null;
        $this->text_color = null;
        $this->background_color = null;
        $this->editArrayIndex = null;
        $this->editId = null;
    }

    public function editSponsorType()
    {
        $this->validate([
            'name' => 'required',
        ]);

        SponsorTypes::where('id', $this->editId)->update([
            'name' => $this->name,
            'description' => $this->description,
            'text_color' => $this->text_color,
            'background_color' => $this->background_color,
        ]);

        $this->finalListOfSponsorTypes[$this->editArrayIndex]['name'] = $this->name;
        $this->finalListOfSponsorTypes[$this->editArrayIndex]['description'] = $this->description;
        $this->finalListOfSponsorTypes[$this->editArrayIndex]['text_color'] = $this->text_color;
        $this->finalListOfSponsorTypes[$this->editArrayIndex]['background_color'] = $this->background_color;

        $this->resetEditSponsorTypeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Sponsor type updated successfully!',
            'text' => ''
        ]);
    }

}
