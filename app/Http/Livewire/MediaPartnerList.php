<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Media;
use App\Models\MediaPartner as MediaPartners;
use Carbon\Carbon;
use Livewire\Component;

class MediaPartnerList extends Component
{
    public $event;
    public $finalListOfMediaPartners = array(), $finalListOfMediaPartnersConst = array();

    // EDIT DETAILS
    public $name, $website;
    public $addMediaPartnerForm;

    // EDIT DATE TIME
    public $mediaPartnerId, $mediaPartnerDateTime, $mediaPartnerArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editMediaPartnerDateTimeForm;

    protected $listeners = ['addMediaPartnerConfirmed' => 'addMediaPartner'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $mediaPartners = MediaPartners::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($mediaPartners->isNotEmpty()) {
            foreach ($mediaPartners as $mediaPartner) {
                array_push($this->finalListOfMediaPartners, [
                    'id' => $mediaPartner->id,
                    'name' => $mediaPartner->name,
                    'website' => $mediaPartner->website,
                    'is_active' => $mediaPartner->is_active,
                    'logo' => Media::where('id', $mediaPartner->logo_media_id)->value('file_url'),
                    'datetime_added' => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfMediaPartnersConst = $this->finalListOfMediaPartners;
        }

        $this->inputNameVariableDateTime = "mediaPartnerDateTime";
        $this->btnUpdateNameMethodDateTime = "editMediaPartnerDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditMediaPartnerDateTimeFields";

        $this->addMediaPartnerForm = false;
        $this->editMediaPartnerDateTimeForm = false;
    }

    public function render()
    {
        return view('livewire.event.media-partners.media-partner-list');
    }

    public function showAddMediaPartner()
    {
        $this->addMediaPartnerForm = true;
    }

    public function resetAddMediaPartnerFields()
    {
        $this->addMediaPartnerForm = false;
        $this->name = null;
        $this->website = null;
    }

    public function addMediaPartnerConfirmation()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addMediaPartnerConfirmed",
        ]);
    }

    public function addMediaPartner(){
        $newMediaPartner = MediaPartners::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'website' => $this->website,
            'datetime_added' => Carbon::now(),
        ]);
        
        array_push($this->finalListOfMediaPartners, [
            'id' => $newMediaPartner->id,
            'name' => $this->name,
            'website' => $this->website,
            'is_active' => true,
            'logo' => null,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfMediaPartnersConst = $this->finalListOfMediaPartners;
        
        $this->resetAddMediaPartnerFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media partner added successfully!',
            'text' => ''
        ]);
    }



    public function updateMediaPartnerStatus($arrayIndex){
        MediaPartners::where('id', $this->finalListOfMediaPartners[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfMediaPartners[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfMediaPartners[$arrayIndex]['is_active'] = !$this->finalListOfMediaPartners[$arrayIndex]['is_active'];
        $this->finalListOfMediaPartnersConst[$arrayIndex]['is_active'] = !$this->finalListOfMediaPartnersConst[$arrayIndex]['is_active'];
    }



    // EDIT DATETIME
    public function showEditMediaPartnerDateTime($mediaPartnerId, $mediaPartnerArrayIndex)
    {
        $mediaPartnerDateTime = MediaPartners::where('id', $mediaPartnerId)->value('datetime_added');

        $this->mediaPartnerId = $mediaPartnerId;
        $this->mediaPartnerDateTime = $mediaPartnerDateTime;
        $this->mediaPartnerArrayIndex = $mediaPartnerArrayIndex;
        $this->editMediaPartnerDateTimeForm = true;
    }

    public function resetEditMediaPartnerDateTimeFields()
    {
        $this->editMediaPartnerDateTimeForm = false;
        $this->mediaPartnerId = null;
        $this->mediaPartnerDateTime = null;
        $this->mediaPartnerArrayIndex = null;
    }
    
    public function editMediaPartnerDateTime()
    {
        $this->validate([
            'mediaPartnerDateTime' => 'required',
        ]);

        MediaPartners::where('id', $this->mediaPartnerId)->update([
            'datetime_added' => $this->mediaPartnerDateTime,
        ]);

        $this->finalListOfMediaPartners[$this->mediaPartnerArrayIndex]['datetime_added'] = Carbon::parse($this->mediaPartnerDateTime)->format('M j, Y g:i A');
        $this->finalListOfMediaPartnersConst[$this->mediaPartnerArrayIndex]['datetime_added'] = Carbon::parse($this->mediaPartnerDateTime)->format('M j, Y g:i A');

        $this->resetEditMediaPartnerDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media Partner Datetime updated successfully!',
            'text' => ''
        ]);
    }
}
