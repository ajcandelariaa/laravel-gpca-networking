<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Speaker as Speakers;
use App\Models\SpeakerType as SpeakerTypes;
use App\Models\Feature as Features;
use App\Models\Media as Medias;
use Carbon\Carbon;
use Livewire\Component;

class SpeakersList extends Component
{
    public $event, $salutations;

    public $finalListOfSpeakers = array(), $finalListOfSpeakersConst = array();

    // Speaker datetime
    public $speakerId, $speakerDateTime, $speakerArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editSpeakerDateTimeForm;

    // Speaker details
    public $category, $type, $salutation, $first_name, $middle_name, $last_name, $company_name, $job_title, $biography_html_text;
    public $categoryChoices = array(), $typeChoices = array();
    public $addSpeakerForm;

    protected $listeners = ['addSpeakerConfirmed' => 'addSpeaker'];

    public function mount($eventId, $eventCategory)
    {
        $this->salutations = config('app.salutations');
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $this->inputNameVariableDateTime = "speakerDateTime";
        $this->btnUpdateNameMethodDateTime = "editSpeakerDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditSpeakerDateTimeFields";

        $this->addSpeakerForm = false;
        $this->editSpeakerDateTimeForm = false;

        $speakers = Speakers::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();
        if ($speakers->isNotEmpty()) {
            foreach ($speakers as $speaker) {
                if ($speaker->feature_id == 0) {
                    $category = $this->event->short_name;
                } else {
                    $feature = Features::where('event_id', $this->event->id)->where('id', $speaker->feature_id)->first();
                    if ($feature) {
                        $category = $feature->short_name;
                    } else {
                        $category = "Others";
                    }
                }

                $speakerType = SpeakerTypes::where('event_id', $this->event->id)->where('id', $speaker->speaker_type_id)->first();
                if ($speakerType) {
                    $type = $speakerType->name;
                } else {
                    $type = "N/A";
                }

                if ($speaker->pfp_media_id) {
                    $speakerPFPUrl = Medias::where('id', $speaker->pfp_media_id)->value('file_url');
                } else {
                    $speakerPFPUrl = asset('assets/images/pfp-placeholder.jpg');
                }

                array_push($this->finalListOfSpeakers, [
                    'id' => $speaker->id,
                    'pfp' => $speakerPFPUrl,
                    'name' => $speaker->salutation . ' ' . $speaker->first_name . ' ' . $speaker->middle_name . ' ' . $speaker->last_name,
                    'category' => $category,
                    'type' => $type,
                    'job_title' => $speaker->job_title,
                    'company_name' => $speaker->company_name,
                    'is_active' => $speaker->is_active,
                    'datetime_added' => Carbon::parse($speaker->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSpeakersConst = $this->finalListOfSpeakers;
        }
    }

    public function render()
    {
        return view('livewire.event.speakers.speakers-list');
    }


    public function showAddSpeakerType()
    {
        return redirect()->route('admin.event.speaker.types.view', ['eventCategory' => $this->event->category, 'eventId' => $this->event->id]);
    }


    // EDIT DATETIME
    public function showEditSpeakerDateTime($speakerId, $speakerArrayIndex)
    {
        $speakerDateTime = Speakers::where('id', $speakerId)->value('datetime_added');

        $this->speakerId = $speakerId;
        $this->speakerDateTime = $speakerDateTime;
        $this->speakerArrayIndex = $speakerArrayIndex;
        $this->editSpeakerDateTimeForm = true;
    }

    public function resetEditSpeakerDateTimeFields()
    {
        $this->editSpeakerDateTimeForm = false;
        $this->speakerId = null;
        $this->speakerDateTime = null;
        $this->speakerArrayIndex = null;
    }

    public function editSpeakerDateTime()
    {
        $this->validate([
            'speakerDateTime' => 'required',
        ]);

        Speakers::where('id', $this->speakerId)->update([
            'datetime_added' => $this->speakerDateTime,
        ]);

        $this->finalListOfSpeakers[$this->speakerArrayIndex]['datetime_added'] = Carbon::parse($this->speakerDateTime)->format('M j, Y g:i A');
        $this->finalListOfSpeakersConst[$this->speakerArrayIndex]['datetime_added'] = Carbon::parse($this->speakerDateTime)->format('M j, Y g:i A');

        $this->resetEditSpeakerDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker Datetime updated successfully!',
            'text' => ''
        ]);
    }



    // ADD SPEAKER
    public function showAddSpeaker()
    {
        $speakerTypes = SpeakerTypes::where('event_id', $this->event->id)->get();

        if ($speakerTypes->isNotEmpty()) {
            foreach ($speakerTypes as $speakerType) {
                array_push($this->typeChoices, [
                    'value' => $speakerType->name,
                    'id' => $speakerType->id,
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

        $this->addSpeakerForm = true;
    }

    public function resetAddSpeakerFields()
    {
        $this->addSpeakerForm = false;
        $this->category = null;
        $this->type = null;
        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->company_name = null;
        $this->job_title = null;
        $this->categoryChoices = array();
        $this->typeChoices = array();
    }

    public function addSpeakerConfirmation()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'category' => 'required',
            'type' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSpeakerConfirmed",
        ]);
    }

    public function addSpeaker()
    {
        $newSpeaker = Speakers::create([
            'event_id' => $this->event->id,
            'feature_id' => $this->category,
            'speaker_type_id' => $this->type,

            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,

            'company_name' => $this->company_name,
            'job_title' => $this->job_title,

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

        array_push($this->finalListOfSpeakers, [
            'id' => $newSpeaker->id,
            'pfp' => asset('assets/images/pfp-placeholder.jpg'),
            'name' => $this->salutation . ' ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
            'category' => $selectedCategory,
            'type' => $selectedType,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'is_active' => true,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfSpeakersConst = $this->finalListOfSpeakers;

        $this->resetAddSpeakerFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker added successfully!',
            'text' => ''
        ]);
    }


    // UPDATE SPEAKER STATUS
    public function updateSpeakerStatus($arrayIndex)
    {
        Speakers::where('id', $this->finalListOfSpeakers[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfSpeakers[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfSpeakers[$arrayIndex]['is_active'] = !$this->finalListOfSpeakers[$arrayIndex]['is_active'];
        $this->finalListOfSpeakersConst[$arrayIndex]['is_active'] = !$this->finalListOfSpeakersConst[$arrayIndex]['is_active'];
    }
}
