<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Sponsor as Sponsors;
use App\Models\SponsorType as SponsorTypes;
use App\Models\Feature as Features;
use App\Models\Media as Medias;
use Carbon\Carbon;
use Livewire\Component;

class SponsorList extends Component
{
    public $event;
    public $finalListOfSponsors = array(), $finalListOfSponsorsConst = array();

    // EDIT DETAILS
    public $feature_id, $sponsor_type_id, $name, $website, $categoryChoices = array(), $typeChoices = array();
    public $addSponsorForm;

    // EDIT DATE TIME
    public $sponsorId, $sponsorDateTime, $sponsorArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editSponsorDateTimeForm;

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
                    'logo' => Medias::where('id', $sponsor->logo_media_id)->value('file_url'),
                    'name' => $sponsor->name,
                    'category' => $category,
                    'type' => $type,
                    'website' => $sponsor->website,
                    'is_active' => $sponsor->is_active,
                    'datetime_added' => Carbon::parse($sponsor->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSponsorsConst = $this->finalListOfSponsors;
        }

        $this->inputNameVariableDateTime = "sponsorDateTime";
        $this->btnUpdateNameMethodDateTime = "editSponsorDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditSponsorDateTimeFields";

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

        array_push($this->categoryChoices, [
            'value' => $this->event->short_name,
            'id' => 0,
        ]);

        $features = Features::where('event_id', $this->event->id)->get();
        if ($features->isNotEmpty()) {
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
            'feature_id' => 'required',
            'sponsor_type_id' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSponsorConfirmed",
        ]);
    }

    public function resetAddSponsorFields()
    {
        $this->addSponsorForm = false;
        $this->feature_id = null;
        $this->sponsor_type_id = null;
        $this->name = null;
        $this->website = null;
        $this->categoryChoices = array();
        $this->typeChoices = array();
    }

    public function addSponsor()
    {
        $newSponsor = Sponsors::create([
            'event_id' => $this->event->id,
            'feature_id' => $this->feature_id,
            'sponsor_type_id' => $this->sponsor_type_id,
            'name' => $this->name,
            'website' => $this->website,
            'datetime_added' => Carbon::now(),
        ]);


        foreach ($this->categoryChoices as $categoryChoice) {
            if ($categoryChoice['id'] == $this->feature_id) {
                $selectedCategory = $categoryChoice['value'];
            }
        }

        foreach ($this->typeChoices as $typeChoice) {
            if ($typeChoice['id'] == $this->sponsor_type_id) {
                $selectedType = $typeChoice['value'];
            }
        }

        array_push($this->finalListOfSponsors, [
            'id' => $newSponsor->id,
            'logo' => null,
            'name' => $this->name,
            'category' => $selectedCategory,
            'type' => $selectedType,
            'website' => $this->website,
            'is_active' => true,
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


    public function updateSponsorStatus($arrayIndex)
    {
        Sponsors::where('id', $this->finalListOfSponsors[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfSponsors[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfSponsors[$arrayIndex]['is_active'] = !$this->finalListOfSponsors[$arrayIndex]['is_active'];
        $this->finalListOfSponsorsConst[$arrayIndex]['is_active'] = !$this->finalListOfSponsorsConst[$arrayIndex]['is_active'];
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
