<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\MediaPartner as MediaPartners;
use Carbon\Carbon;
use Livewire\Component;

class MediaPartnerList extends Component
{
    public $event;

    public $finalListOfMediaPartners = array(), $finalListOfMediaPartnersConst = array();

    public $addMediaPartnerForm, $name, $website;

    public $mediaPartnerId, $mediaPartnerDateTime, $mediaPartnerArrayIndex, $editMediaPartnerDateTimeForm;

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
                    'active' => $mediaPartner->active,
                    'logo' => $mediaPartner->logo,
                    'datetime_added' => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfMediaPartnersConst = $this->finalListOfMediaPartners;
        }

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

    public function addMediaPartnerConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'website' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addMediaPartnerConfirmed",
        ]);
    }

    public function cancelAddMediaPartner()
    {
        $this->resetAddMediaPartnerFields();
    }


    public function resetAddMediaPartnerFields()
    {
        $this->addMediaPartnerForm = false;
        $this->name = null;
        $this->website = null;
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
            'active' => true,
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

    public function updateMediaPartnerStatus($arrayIndex, $mediaPartnerId, $status){
        if($status){
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        MediaPartners::where('id', $mediaPartnerId)->update([
            'active' => $newStatus,
        ]);

        $this->finalListOfMediaPartners[$arrayIndex]['active'] = $newStatus;
        $this->finalListOfMediaPartnersConst[$arrayIndex]['active'] = $newStatus;
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

    public function cancelEditMediaPartnerDateTime()
    {
        $this->resetEditMediaPartnerDateTimeFields();
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
