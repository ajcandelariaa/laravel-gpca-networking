<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Session as Sessions;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Livewire\Component;

class SessionList extends Component
{
    public $event;
    public $finalListOfSessions = array(), $finalListOfSessionsConst = array();

    // Add Sesssion
    public $feature_id, $session_date, $session_day, $session_type, $title, $start_time, $end_time, $categoryChoices = array();
    public $addSessionForm;

    protected $listeners = ['addSessionConfirmed' => 'addSession'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $sessions = Sessions::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($sessions->isNotEmpty()) {
            foreach ($sessions as $session) {
                
                if ($session->feature_id == 0) {
                    $categoryName = $this->event->short_name;
                } else {
                    $feature = Features::where('event_id', $this->event->id)->where('id', $session->feature_id)->first();
                    if ($feature) {
                        $categoryName = $feature->short_name;
                    } else {
                        $categoryName = "Others";
                    }
                }

                if($session->end_time == "none"){
                    $timings = $session->start_time . ' - ' . 'onwards';
                } else {
                    $timings = $session->start_time . ' - ' . $session->end_time;
                }

                array_push($this->finalListOfSessions, [
                    'id' => $session->id,
                    'categoryName' => $categoryName,
                    'session_date' => Carbon::parse($session->session_date)->format('F d, Y'),
                    'session_day' => $session->session_day,
                    'title' => $session->title,
                    'timings' => $timings,
                    'is_active' => $session->is_active,
                    'datetime_added' => Carbon::parse($session->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfSessionsConst = $this->finalListOfSessions;
        }

        $this->addSessionForm = false;
    }

    public function render()
    {
        return view('livewire.event.sessions.session-list');
    }

    public function showAddSession()
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

        $this->addSessionForm = true;
    }

    public function addSessionConfirmation()
    {
        $this->validate([
            'feature_id' => 'required',
            'session_date' => 'required',
            'session_day' => 'required',
            'title' => 'required',
            'start_time' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addSessionConfirmed",
        ]);
    }

    public function resetAddSessionFields()
    {
        $this->addSessionForm = false;
        $this->feature_id = null;
        $this->session_date = null;
        $this->session_day = null;
        $this->session_type = null;
        $this->title = null;
        $this->start_time = null;
        $this->end_time = null;
        $this->categoryChoices = array();
    }

    public function addSession()
    {
        if($this->end_time == "" || $this->end_time == null){
            $finalEndTime = "none";
            $forArrayTimings = $this->start_time . ' - ' . 'onwards';
        } else {
            $finalEndTime = $this->end_time;
            $forArrayTimings = $this->start_time . ' - ' . $this->end_time;
        }

        if($this->session_type == "" || $this->session_type == null){
            $finalSessionType = null;
        } else {
            $finalSessionType = $this->session_type;
        }

        $newSession = Sessions::create([
            'event_id' => $this->event->id,
            'feature_id' => $this->feature_id,
            'session_date' => $this->session_date,
            'session_day' => $this->session_day,
            'session_type' => $finalSessionType,
            'title' => $this->title,
            'start_time' => $this->start_time,
            'end_time' => $finalEndTime,
            'datetime_added' => Carbon::now(),
        ]);


        foreach ($this->categoryChoices as $categoryChoice) {
            if ($categoryChoice['id'] == $this->feature_id) {
                $selectedCategory = $categoryChoice['value'];
            }
        }
        
        array_push($this->finalListOfSessions, [
            'id' => $newSession->id,
            'categoryName' => $selectedCategory,
            'session_date' => $this->session_date,
            'session_day' => $this->session_day,
            'title' => $this->title,
            'timings' => $forArrayTimings,
            'is_active' => true,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfSessionsConst = $this->finalListOfSessions;

        $this->resetAddSessionFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Session added successfully!',
            'text' => ''
        ]);
    }


    public function updateSessionStatus($arrayIndex)
    {
        Sessions::where('id', $this->finalListOfSessions[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfSessions[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfSessions[$arrayIndex]['is_active'] = !$this->finalListOfSessions[$arrayIndex]['is_active'];
        $this->finalListOfSessionsConst[$arrayIndex]['is_active'] = !$this->finalListOfSessionsConst[$arrayIndex]['is_active'];
    }
}
