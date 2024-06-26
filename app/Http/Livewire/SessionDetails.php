<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Session as Sessions;
use App\Models\Feature as Features;
use App\Models\Media;
use App\Models\Speaker as Speakers;
use App\Models\SessionSpeaker as SessionSpeakers;
use App\Models\SessionSpeakerType as SessionSpeakerTypes;
use App\Models\Sponsor;
use App\Models\SponsorType;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class SessionDetails extends Component
{
    public $event, $sessionData;

    public $editSessionDetailsForm, $category, $session_date, $session_day, $session_type, $title, $description, $start_time, $end_time, $location, $categoryChoices = array();
    public $sponsor_id, $sponsorsChoices = array();

    public $addSessionSpeakerForm, $session_speaker_type_id, $speaker_ids = [], $speakerTypeChoices = array(), $speakerChoices = array();

    protected $listeners = ['editSessionDetailsConfirmed' => 'editSessionDetails', 'addSessionSpeakerConfirmed' => 'addSessionSpeaker',];

    public function mount($eventId, $eventCategory, $sessionData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->sessionData = $sessionData;
        $this->editSessionDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.sessions.session-details');
    }

    // EDIT SESSION DETAILS
    public function showEditSessionDetails()
    {
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

        $sponsors = Sponsor::where('event_id', $this->event->id)->where('is_active', true)->get();
        if ($sponsors->isNotEmpty()) {
            foreach ($sponsors as $sponsor) {
                $sponsorTypeName = SponsorType::where('id', $sponsor->sponsor_type_id)->value('name');
                array_push($this->sponsorsChoices, [
                    'id' => $sponsor->id,
                    'option' => $sponsor->name . ' - ' . $sponsorTypeName,
                ]);
            }
        }

        $this->category = $this->sessionData['sessionFeatureId'];
        $this->session_date = $this->sessionData['sessionDate'];
        $this->session_day = $this->sessionData['sessionDay'];
        $this->session_type = $this->sessionData['sessionType'];
        $this->title = $this->sessionData['sessionTitle'];
        $this->description = $this->sessionData['sessionDescription'];
        $this->start_time = $this->sessionData['sessionStartTime'];
        $this->end_time = $this->sessionData['sessionEndTime'];
        $this->location = $this->sessionData['sessionLocation'];
        $this->sponsor_id = $this->sessionData['sessionSponsorLogo']['sponsor_id'];
        $this->editSessionDetailsForm = true;
    }

    public function cancelEditSessionDetails()
    {
        $this->resetEditSessionDetailsFields();
    }

    public function resetEditSessionDetailsFields()
    {
        $this->editSessionDetailsForm = false;
        $this->category = null;
        $this->session_date = null;
        $this->session_day = null;
        $this->session_type = null;
        $this->title = null;
        $this->description = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->location = null;
        $this->sponsor_id = null;
        $this->sponsorsChoices = array();
        $this->categoryChoices = array();
    }

    public function editSessionDetailsConfirmation()
    {
        $this->validate([
            'category' => 'required',
            'session_date' => 'required',
            'session_day' => 'required',
            'title' => 'required',
            'start_time' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editSessionDetailsConfirmed",
        ]);
    }

    public function editSessionDetails()
    {
        if ($this->end_time == "" || $this->end_time == null) {
            $finalEndTime = "none";
            $forArrayEndTime = 'onwards';
        } else {
            $finalEndTime = $this->end_time;
            $forArrayEndTime = $this->end_time;
        }

        if ($this->session_type == "" || $this->session_type == null) {
            $finalSessionType = null;
        } else {
            $finalSessionType = $this->session_type;
        }

        if($this->sponsor_id == "" || $this->sponsor_id == null){
            $this->sponsor_id = null;
        }

        Sessions::where('id', $this->sessionData['sessionId'])->update([
            'feature_id' => $this->category,
            'session_date' => $this->session_date,
            'session_day' => $this->session_day,
            'session_type' => $finalSessionType,
            'title' => $this->title,
            'description_html_text' => $this->description,
            'start_time' => $this->start_time,
            'end_time' => $finalEndTime,
            'location' => $this->location,
            'sponsor_id' => $this->sponsor_id,
        ]);

        foreach ($this->categoryChoices as $categoryChoice) {
            if ($categoryChoice['id'] == $this->category) {
                $selectedCategory = $categoryChoice['value'];
            }
        }

        $this->sessionData['sessionCategoryName'] = $selectedCategory;
        $this->sessionData['sessionFeatureId'] = $this->category;

        $this->sessionData['sessionDate'] = $this->session_date;
        $this->sessionData['sessionDateName'] = Carbon::parse($this->session_date)->format('F d, Y');

        $this->sessionData['sessionDay'] = $this->session_day;
        $this->sessionData['sessionType'] = $this->session_type;
        $this->sessionData['sessionTitle'] = $this->title;
        $this->sessionData['sessionDescription'] = $this->description;
        $this->sessionData['sessionStartTime'] = $this->start_time;
        $this->sessionData['sessionEndTime'] = $forArrayEndTime;
        $this->sessionData['sessionLocation'] = $this->location;


        if ($this->sponsor_id == "" || $this->sponsor_id == null) {
            $this->sessionData['sessionSponsorLogo'] = [
                'sponsor_id' => null,
                'name' => null,
                'url' => null,
            ];
        } else {
            $sponsorLogoId = Sponsor::where('id', $this->sponsor_id)->where('event_id', $this->event->id)->where('is_active', true)->value('logo_media_id');
            $sessionSponsorLogo = Media::where('id', $sponsorLogoId)->value('file_url');

            $sponsorName = null;
            foreach($this->sponsorsChoices as $sponsorChoice){
                if($this->sponsor_id == $sponsorChoice['id']){
                    $sponsorName = $sponsorChoice['option'];
                }
            }

            $this->sessionData['sessionSponsorLogo'] = [
                'sponsor_id' => $this->sponsor_id,
                'name' => $sponsorName,
                'url' => $sessionSponsorLogo,
            ];
        }

        $this->resetEditSessionDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Session details updated succesfully!',
            'text' => "",
        ]);
    }

    public function clearEndTime()
    {
        $this->end_time = null;
    }



    public function showAddSpeakerType()
    {
        return redirect()->route('admin.event.session.speaker.types.view', ['eventCategory' => $this->event->category, 'eventId' => $this->event->id, 'sessionId' => $this->sessionData['sessionId']]);
    }



    public function showAddSpeaker()
    {
        $speakers = Speakers::where('event_id', $this->event->id)->where('feature_id', $this->sessionData['sessionFeatureId'])->orderBy('datetime_added')->get();

        if ($speakers->isNotEmpty()) {
            foreach ($speakers as $speaker) {
                $speakerName = $speaker->salutation . ' ' . $speaker->first_name . ' ' . $speaker->middle_name . ' ' . $speaker->last_name;

                if ($speaker->pfp) {
                    $speakerPFP = Storage::url($speaker->pfp);
                } else {
                    $speakerPFP = asset('assets/images/pfp-placeholder.jpg');
                }

                array_push($this->speakerChoices, [
                    'speakerId' => $speaker->id,
                    'speakerName' => $speakerName,
                    'speakerPFP' => $speakerPFP,
                ]);
            }
        }


        $speakerTypes = SessionSpeakerTypes::where('event_id', $this->event->id)->where('session_id', $this->sessionData['sessionId'])->orderBy('datetime_added')->get();
        if ($speakerTypes->isNotEmpty()) {
            foreach ($speakerTypes as $speakerType) {
                array_push($this->speakerTypeChoices, [
                    'speakerTypeId' => $speakerType->id,
                    'speakerTypeName' => $speakerType->name,
                ]);
            }
        }

        $this->addSessionSpeakerForm = true;
    }

    public function addSessionSpeakerConfirmation()
    {
        $this->validate([
            'speaker_ids' => 'required',
        ]);
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSessionSpeakerConfirmed",
        ]);
    }

    public function cancelAddSessionSpeaker()
    {
        $this->resetAddSessionSpeakerFields();
    }

    public function resetAddSessionSpeakerFields()
    {
        $this->addSessionSpeakerForm = false;
        $this->session_speaker_type_id = null;
        $this->speaker_ids = [];
        $this->speakerChoices = array();
        $this->speakerTypeChoices = array();
    }

    public function addSessionSpeaker()
    {
        foreach ($this->speaker_ids as $speakerId) {
            if ($this->session_speaker_type_id == null) {
                $finalSessionSpeakerTypeId = 0;
            } else {
                $finalSessionSpeakerTypeId = $this->session_speaker_type_id;
            }

            $newSessionSpeaker = SessionSpeakers::create([
                'event_id' => $this->event->id,
                'session_id' => $this->sessionData['sessionId'],
                'session_speaker_type_id' => $finalSessionSpeakerTypeId,
                'speaker_id' => $speakerId,
            ]);

            $checker = 0;
            $groupIndex = null;

            foreach ($this->sessionData['finalSessionSpeakerGroup'] as $index => $group) {
                if ($group['sessionSpeakerTypeId'] == $finalSessionSpeakerTypeId) {
                    $checker++;
                    $groupIndex = $index;
                }
            }

            foreach ($this->speakerChoices as $speakerChoice) {
                if ($speakerId == $speakerChoice['speakerId']) {
                    $speakerName = $speakerChoice['speakerName'];
                    $speakerPFP = $speakerChoice['speakerPFP'];
                }
            }

            if ($this->session_speaker_type_id == null) {
                $finalSessionSpeakerTypeName = null;
            } else {
                foreach ($this->speakerTypeChoices as $speakerTypeChoice) {
                    if ($this->session_speaker_type_id == $speakerTypeChoice['speakerTypeId']) {
                        $finalSessionSpeakerTypeName = $speakerTypeChoice['speakerTypeName'];
                    }
                }
            }

            if ($checker > 0) {
                array_push($this->sessionData['finalSessionSpeakerGroup'][$groupIndex]['speakers'], [
                    'sessionSpeakerId' => $newSessionSpeaker->id,
                    'speakerId' => $speakerId,
                    'speakerName' => $speakerName,
                    'speakerPFP' => $speakerPFP,
                ]);
            } else {
                array_push($this->sessionData['finalSessionSpeakerGroup'], [
                    'sessionSpeakerTypeId' => $finalSessionSpeakerTypeId,
                    'sessionSpeakerTypeName' => $finalSessionSpeakerTypeName,
                    'speakers' => array(),
                ]);

                $lastArrayIndex = count($this->sessionData['finalSessionSpeakerGroup']) - 1;

                array_push($this->sessionData['finalSessionSpeakerGroup'][$lastArrayIndex]['speakers'], [
                    'sessionSpeakerId' => $newSessionSpeaker->id,
                    'speakerId' => $speakerId,
                    'speakerName' => $speakerName,
                    'speakerPFP' => $speakerPFP,
                ]);
            }
        }

        $this->resetAddSessionSpeakerFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Session speaker added successfully!',
            'text' => ''
        ]);
    }


    public function removeSessionSpeaker($speakerGroupIndex, $speakerIndex, $sessionSpeakerId)
    {
        $newSessionSpeakerGroup = array();

        SessionSpeakers::where('id', $sessionSpeakerId)->delete();

        if (count($this->sessionData['finalSessionSpeakerGroup'][$speakerGroupIndex]['speakers']) == 1) {
            foreach ($this->sessionData['finalSessionSpeakerGroup'] as $groupIndex => $group) {
                if ($groupIndex != $speakerGroupIndex) {
                    array_push($newSessionSpeakerGroup, $group);
                }
            }
            $this->sessionData['finalSessionSpeakerGroup'] = $newSessionSpeakerGroup;
        } else {
            $tempSpeaker = array();
            foreach ($this->sessionData['finalSessionSpeakerGroup'][$speakerGroupIndex]['speakers'] as $innerSpeakerIndex => $speaker) {
                if ($innerSpeakerIndex != $speakerIndex) {
                    array_push($tempSpeaker, $speaker);
                }
            }
            $this->sessionData['finalSessionSpeakerGroup'][$speakerGroupIndex]['speakers'] = $tempSpeaker;
        }
    }
}
