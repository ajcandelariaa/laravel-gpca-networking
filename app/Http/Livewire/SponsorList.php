<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Sponsor as Sponsors;
use App\Models\SponsorType as SponsorTypes;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Livewire\Component;

class SponsorList extends Component
{
    public $event;

    public $finalListOfSponsors = array(), $finalListOfSponsorsConst = array();

    public $addSponsorForm, $category, $type, $name, $website, $categoryChoices = array(), $typeChoices = array();

    public $sponsorId, $sponsorDateTime, $sponsorArrayIndex, $editSponsorDateTimeForm;

    protected $listeners = ['addSponsorConfirmed' => 'addSponsor'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $sponsors = Sponsors::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($sponsors->isNotEmpty()) {
            foreach ($sponsors as $sponsor) {
                if ($sponsor->feature_id == 0) {
                    $category = $this->event->short_name;
                } else {
                    $feature = Features::where('event_id', $this->event->id)->where('id', $sponsor->feature_id)->first();
                    if ($feature) {
                        $category = $feature->short_name;
                    } else {
                        $category = "Others";
                    }
                }

                $sponsorType = SponsorTypes::where('event_id', $this->event->id)->where('id', $sponsor->sponsor_type_id)->first();
                if ($sponsorType) {
                    $type = $sponsorType->name;
                } else {
                    $type = "N/A";
                }

                array_push($this->finalListOfSponsors, [
                    'id' => $sponsor->id,
                    'logo' => $sponsor->logo,
                    'name' => $sponsor->name,
                    'category' => $category,
                    'type' => $type,
                    'active' => $sponsor->active,
                    'datetime_added' => Carbon::parse($sponsor->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSponsorsConst = $this->finalListOfSponsors;
        }

        $this->addSponsorForm = false;
        $this->editSponsorDateTimeForm = false;
    }

    public function render()
    {
        return view('livewire.event.sponsors.sponsor-list');
    }

    public function showAddSponsorType()
    {
        return redirect()->route('admin.event.sponsor.types.view', ['eventCategory' => $this->event->category, 'eventId' => $this->event->id]);
    }


    public function showAddSponsor()
    {
        $sponsorTypes = SponsorTypes::where('event_id', $this->event->id)->get();

        if ($sponsorTypes->isNotEmpty()) {
            foreach ($sponsorTypes as $sponsorType) {
                array_push($this->typeChoices, [
                    'value' => $sponsorType->name,
                    'id' => $sponsorType->id,
                ]);
            }
        }

        $features = Features::where('event_id', $this->event->id)->get();
        if ($features->isNotEmpty()) {

            array_push($this->categoryChoices, [
                'value' => $this->event->short_name,
                'id' => 0,
            ]);

            foreach ($features as $feature) {
                array_push($this->categoryChoices, [
                    'value' => $feature->short_name,
                    'id' => $feature->id,
                ]);
            }
        }

        $this->addSponsorForm = true;
    }

    public function addSponsorConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'website' => 'required',
            'category' => 'required',
            'type' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSponsorConfirmed",
        ]);
    }

    public function cancelAddSponsor()
    {
        $this->resetAddSponsorFields();
    }

    public function resetAddSponsorFields()
    {
        $this->addSponsorForm = false;
        $this->category = null;
        $this->type = null;
        $this->name = null;
        $this->website = null;
        $this->categoryChoices = array();
        $this->typeChoices = array();
    }

    public function addSponsor()
    {
        $newSponsor = Sponsors::create([
            'event_id' => $this->event->id,
            'feature_id' => $this->category,
            'sponsor_type_id' => $this->type,
            'name' => $this->name,
            'website' => $this->website,
            'datetime_added' => Carbon::now(),
        ]);


        foreach ($this->categoryChoices as $categoryChoice) {
            if ($categoryChoice['id'] == $this->category) {
                $selectedCategory = $categoryChoice['value'];
            }
        }

        foreach ($this->typeChoices as $typeChoice) {
            if ($typeChoice['id'] == $this->type) {
                $selectedType = $typeChoice['value'];
            }
        }

        array_push($this->finalListOfSponsors, [
            'id' => $newSponsor->id,
            'logo' => null,
            'name' => $this->name,
            'category' => $selectedCategory,
            'type' => $selectedType,
            'active' => true,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfSponsorsConst = $this->finalListOfSponsors;

        $this->resetAddSponsorFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Sponsor added successfully!',
            'text' => ''
        ]);
    }


    public function updateSponsorStatus($arrayIndex, $sponsorId, $status)
    {
        if ($status) {
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        Sponsors::where('id', $sponsorId)->update([
            'active' => $newStatus,
        ]);

        $this->finalListOfSponsors[$arrayIndex]['active'] = $newStatus;
        $this->finalListOfSponsorsConst[$arrayIndex]['active'] = $newStatus;
    }



    // EDIT DATETIME
    public function showEditSponsorDateTime($sponsorId, $sponsorArrayIndex)
    {
        $sponsorDateTime = Sponsors::where('id', $sponsorId)->value('datetime_added');

        $this->sponsorId = $sponsorId;
        $this->sponsorDateTime = $sponsorDateTime;
        $this->sponsorArrayIndex = $sponsorArrayIndex;
        $this->editSponsorDateTimeForm = true;
    }

    public function cancelEditSponsorDateTime()
    {
        $this->resetEditSponsorDateTimeFields();
    }

    public function resetEditSponsorDateTimeFields()
    {
        $this->editSponsorDateTimeForm = false;
        $this->sponsorId = null;
        $this->sponsorDateTime = null;
        $this->sponsorArrayIndex = null;
    }

    public function editSponsorDateTime()
    {
        $this->validate([
            'sponsorDateTime' => 'required',
        ]);

        Sponsors::where('id', $this->sponsorId)->update([
            'datetime_added' => $this->sponsorDateTime,
        ]);

        $this->finalListOfSponsors[$this->sponsorArrayIndex]['datetime_added'] = Carbon::parse($this->sponsorDateTime)->format('M j, Y g:i A');
        $this->finalListOfSponsorsConst[$this->sponsorArrayIndex]['datetime_added'] = Carbon::parse($this->sponsorDateTime)->format('M j, Y g:i A');

        $this->resetEditSponsorDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Sponsor updated successfully!',
            'text' => ''
        ]);
    }
}
