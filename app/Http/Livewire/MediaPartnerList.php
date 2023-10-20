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

    public $addMediaPartnerForm, $name, $link;

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
                    'bio' => $mediaPartner->bio,
                    'email_address' => $mediaPartner->email_address,
                    'mobile_number' => $mediaPartner->mobile_number,
                    'link' => $mediaPartner->link,
                    'active' => $mediaPartner->active,
                    'datetime_added' => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfMediaPartnersConst = $this->finalListOfMediaPartners;
        }

        $this->addMediaPartnerForm = false;
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
            'link' => 'required',
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
        $this->link = null;
    }

    public function addMediaPartner(){
        $newMediaPartner = MediaPartners::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'link' => $this->link,
            'datetime_added' => Carbon::now(),
        ]);

        
        array_push($this->finalListOfMediaPartners, [
            'id' => $newMediaPartner->id,
            'name' => $this->name,
            'bio' => null,
            'email_address' => null,
            'mobile_number' => null,
            'link' => $this->link,
            'active' => true,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);
    }
}
