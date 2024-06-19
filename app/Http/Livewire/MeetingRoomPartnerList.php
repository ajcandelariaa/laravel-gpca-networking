<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Media as Medias;
use App\Models\MeetingRoomPartner as MeetingRoomPartners;
use Carbon\Carbon;
use Livewire\Component;

class MeetingRoomPartnerList extends Component
{
    public $event;
    public $finalListOfMeetingRoomPartners = array(), $finalListOfMeetingRoomPartnersConst = array();

    // ADD MRP
    public $name, $website, $location;
    public $addMeetingRoomPartnerForm;

    // EDIT DATE TIME
    public $meetingRoomPartnerId, $meetingRoomPartnerDateTime, $meetingRoomPartnerArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editMeetingRoomPartnerDateTimeForm;

    protected $listeners = ['addMeetingRoomPartnerConfirmed' => 'addMeetingRoomPartner'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $meetingRoomPartners = MeetingRoomPartners::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($meetingRoomPartners->isNotEmpty()) {
            foreach ($meetingRoomPartners as $meetingRoomPartner) {
                array_push($this->finalListOfMeetingRoomPartners, [
                    'id' => $meetingRoomPartner->id,
                    'name' => $meetingRoomPartner->name,
                    'location' => $meetingRoomPartner->location,
                    'website' => $meetingRoomPartner->website,
                    'is_active' => $meetingRoomPartner->is_active,
                    'logo' => Medias::where('id', $meetingRoomPartner->logo_media_id)->value('file_url'),
                    'datetime_added' => Carbon::parse($meetingRoomPartner->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfMeetingRoomPartnersConst = $this->finalListOfMeetingRoomPartners;
        }

        $this->inputNameVariableDateTime = "meetingRoomPartnerDateTime";
        $this->btnUpdateNameMethodDateTime = "editMeetingRoomPartnerDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditMeetingRoomPartnerDateTimeFields";

        $this->addMeetingRoomPartnerForm = false;
        $this->editMeetingRoomPartnerDateTimeForm = false;
    }

    public function render()
    {
        return view('livewire.event.meeting-room-partners.meeting-room-partner-list');
    }
    
    public function showAddMeetingRoomPartner()
    {
        $this->addMeetingRoomPartnerForm = true;
    }

    public function addMeetingRoomPartnerConfirmation()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addMeetingRoomPartnerConfirmed",
        ]);
    }

    public function resetAddMeetingRoomPartnerFields()
    {
        $this->addMeetingRoomPartnerForm = false;
        $this->name = null;
        $this->website = null;
        $this->location = null;
    }

    public function addMeetingRoomPartner(){
        $newMeetingRoomPartner = MeetingRoomPartners::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'website' => $this->website,
            'location' => $this->location,
            'datetime_added' => Carbon::now(),
        ]);
        
        array_push($this->finalListOfMeetingRoomPartners, [
            'id' => $newMeetingRoomPartner->id,
            'name' => $this->name,
            'location' => $this->location,
            'website' => $this->website,
            'is_active' => true,
            'logo' => null,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfMeetingRoomPartnersConst = $this->finalListOfMeetingRoomPartners;
        
        $this->resetAddMeetingRoomPartnerFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Meeting room partner added successfully!',
            'text' => ''
        ]);
    }


    
    public function updateMeetingRoomPartnerStatus($arrayIndex){
        MeetingRoomPartners::where('id', $this->finalListOfMeetingRoomPartners[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfMeetingRoomPartners[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfMeetingRoomPartners[$arrayIndex]['is_active'] = !$this->finalListOfMeetingRoomPartners[$arrayIndex]['is_active'];
        $this->finalListOfMeetingRoomPartnersConst[$arrayIndex]['is_active'] = !$this->finalListOfMeetingRoomPartnersConst[$arrayIndex]['is_active'];
    }



    // EDIT DATETIME
    public function showEditMeetingRoomPartnerDateTime($meetingRoomPartnerId, $meetingRoomPartnerArrayIndex)
    {
        $meetingRoomPartnerDateTime = MeetingRoomPartners::where('id', $meetingRoomPartnerId)->value('datetime_added');

        $this->meetingRoomPartnerId = $meetingRoomPartnerId;
        $this->meetingRoomPartnerDateTime = $meetingRoomPartnerDateTime;
        $this->meetingRoomPartnerArrayIndex = $meetingRoomPartnerArrayIndex;
        $this->editMeetingRoomPartnerDateTimeForm = true;
    }

    public function resetEditMeetingRoomPartnerDateTimeFields()
    {
        $this->editMeetingRoomPartnerDateTimeForm = false;
        $this->meetingRoomPartnerId = null;
        $this->meetingRoomPartnerDateTime = null;
        $this->meetingRoomPartnerArrayIndex = null;
    }
    
    public function editMeetingRoomPartnerDateTime()
    {
        $this->validate([
            'meetingRoomPartnerDateTime' => 'required',
        ]);

        MeetingRoomPartners::where('id', $this->meetingRoomPartnerId)->update([
            'datetime_added' => $this->meetingRoomPartnerDateTime,
        ]);

        $this->finalListOfMeetingRoomPartners[$this->meetingRoomPartnerArrayIndex]['datetime_added'] = Carbon::parse($this->meetingRoomPartnerDateTime)->format('M j, Y g:i A');
        $this->finalListOfMeetingRoomPartnersConst[$this->meetingRoomPartnerArrayIndex]['datetime_added'] = Carbon::parse($this->meetingRoomPartnerDateTime)->format('M j, Y g:i A');

        $this->resetEditMeetingRoomPartnerDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media Partner Datetime updated successfully!',
            'text' => ''
        ]);
    }
}
