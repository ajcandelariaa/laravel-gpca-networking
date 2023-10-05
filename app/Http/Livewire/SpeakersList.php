<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Speaker as Speakers;
use Carbon\Carbon;
use Livewire\Component;

class SpeakersList extends Component
{
    public $event, $salutations;

    public $finalListOfSpeakers = array(), $finalListOfSpeakersConst = array();

    public $searchTerm;

    public $speakerId, $speakerDateTime, $speakerArrayIndex, $editSpeakerDateTimeForm;

    // Speaker details
    public $salutation, $first_name, $middle_name, $last_name, $company_name, $job_title, $bio, $addSpeakerForm;

    protected $listeners = ['addSpeakerConfirmed' => 'addSpeaker'];

    public function mount($eventId, $eventCategory)
    {
        $this->salutations = config('app.salutations');
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->addSpeakerForm = false;
        $this->editSpeakerDateTimeForm = false;

        $speakers = Speakers::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();
        if ($speakers->isNotEmpty()) {
            foreach ($speakers as $speaker) {
                array_push($this->finalListOfSpeakers, [
                    'id' => $speaker->id,
                    'name' => $speaker->salutation . ' ' . $speaker->first_name . ' ' . $speaker->middle_name . ' ' . $speaker->last_name,
                    'job_title' => $speaker->job_title,
                    'company_name' => $speaker->company_name,
                    'active' => $speaker->active,
                    'datetime_added' => Carbon::parse($speaker->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSpeakersConst = $this->finalListOfSpeakers;
        }
        // dd($this->finalListOfSpeakers);
    }

    public function render()
    {
        return view('livewire.event.speakers.speakers-list');
    }


    public function search()
    {
        if (empty($this->searchTerm)) {
            $this->finalListOfSpeakers = $this->finalListOfSpeakersConst;
        } else {
            $this->finalListOfSpeakers = collect($this->finalListOfSpeakersConst)
                ->filter(function ($item) {
                    return str_contains(strtolower($item['name']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['company_name']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['job_title']), strtolower($this->searchTerm)) ||
                        str_contains(strtolower($item['datetime_added']), strtolower($this->searchTerm));
                })->all();
        }
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

    public function cancelEditSpeakerDateTime()
    {
        $this->resetEditSpeakerDateTimeFields();
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
        $this->addSpeakerForm = true;
    }

    public function cancelAddSpeaker()
    {
        $this->resetAddSpeakerFields();
    }

    public function resetAddSpeakerFields()
    {
        $this->addSpeakerForm = false;
        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->company_name = null;
        $this->job_title = null;
    }

    public function addSpeakerConfirmation()
    {
        $this->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'job_title' => 'required',
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

            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,

            'company_name' => $this->company_name,
            'job_title' => $this->job_title,

            'active' => true,

            'datetime_added' => Carbon::now(),
        ]);

        array_push($this->finalListOfSpeakers, [
            'id' => $newSpeaker->id,
            'name' => $this->salutation . ' ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'active' => true,
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

    public function updateSpeakerStatus($arrayIndex, $speakerId, $status){
        if($status){
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        Speakers::where('id', $speakerId)->update([
            'active' => $newStatus,
        ]);

        $this->finalListOfSpeakers[$arrayIndex]['active'] = $newStatus;
        $this->finalListOfSpeakersConst[$arrayIndex]['active'] = $newStatus;
    }
}

