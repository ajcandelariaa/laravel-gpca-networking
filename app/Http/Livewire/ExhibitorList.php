<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Exhibitor as Exhibitors;
use Carbon\Carbon;
use Livewire\Component;

class ExhibitorList extends Component
{
    public $event;

    public $finalListOfExhibitors = array(), $finalListOfExhibitorsConst = array();

    public $addExhibitorForm, $name, $link, $stand_number;

    public $exhibitorId, $exhibitorDateTime, $exhibitorArrayIndex, $editExhibitorDateTimeForm;

    protected $listeners = ['addExhibitorConfirmed' => 'addExhibitor'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $exhibitors = Exhibitors::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($exhibitors->isNotEmpty()) {
            foreach ($exhibitors as $exhibitor) {
                array_push($this->finalListOfExhibitors, [
                    'id' => $exhibitor->id,
                    'name' => $exhibitor->name,
                    'stand_number' => $exhibitor->stand_number,
                    'link' => $exhibitor->link,
                    'active' => $exhibitor->active,
                    'logo' => $exhibitor->logo,
                    'datetime_added' => Carbon::parse($exhibitor->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfExhibitorsConst = $this->finalListOfExhibitors;
        }

        $this->addExhibitorForm = false;
        $this->editExhibitorDateTimeForm = false;
    }

    public function render()
    {
        return view('livewire.event.exhibitors.exhibitor-list');
    }

    public function showAddExhibitor()
    {
        $this->addExhibitorForm = true;
    }

    public function addExhibitorConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'link' => 'required',
            'stand_number' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addExhibitorConfirmed",
        ]);
    }

    public function cancelAddExhibitor()
    {
        $this->resetAddExhibitorFields();
    }

    public function resetAddExhibitorFields()
    {
        $this->addExhibitorForm = false;
        $this->name = null;
        $this->link = null;
        $this->stand_number = null;
    }

    public function addExhibitor(){
        $newExhibitor = Exhibitors::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'link' => $this->link,
            'stand_number' => $this->stand_number,
            'datetime_added' => Carbon::now(),
        ]);
        
        array_push($this->finalListOfExhibitors, [
            'id' => $newExhibitor->id,
            'name' => $this->name,
            'stand_number' => $this->stand_number,
            'link' => $this->link,
            'active' => true,
            'logo' => null,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfExhibitorsConst = $this->finalListOfExhibitors;
        
        $this->resetAddExhibitorFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Exhibitor added successfully!',
            'text' => ''
        ]);
    }




    public function updateExhibitorStatus($arrayIndex, $exhibitorId, $status){
        if($status){
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        Exhibitors::where('id', $exhibitorId)->update([
            'active' => $newStatus,
        ]);

        $this->finalListOfExhibitors[$arrayIndex]['active'] = $newStatus;
        $this->finalListOfExhibitorsConst[$arrayIndex]['active'] = $newStatus;
    }



    // EDIT DATETIME
    public function showEditExhibitorDateTime($exhibitorId, $exhibitorArrayIndex)
    {
        $exhibitorDateTime = Exhibitors::where('id', $exhibitorId)->value('datetime_added');

        $this->exhibitorId = $exhibitorId;
        $this->exhibitorDateTime = $exhibitorDateTime;
        $this->exhibitorArrayIndex = $exhibitorArrayIndex;
        $this->editExhibitorDateTimeForm = true;
    }

    public function cancelEditExhibitorDateTime()
    {
        $this->resetEditExhibitorDateTimeFields();
    }

    public function resetEditExhibitorDateTimeFields()
    {
        $this->editExhibitorDateTimeForm = false;
        $this->exhibitorId = null;
        $this->exhibitorDateTime = null;
        $this->exhibitorArrayIndex = null;
    }
    
    public function editExhibitorDateTime()
    {
        $this->validate([
            'exhibitorDateTime' => 'required',
        ]);

        Exhibitors::where('id', $this->exhibitorId)->update([
            'datetime_added' => $this->exhibitorDateTime,
        ]);

        $this->finalListOfExhibitors[$this->exhibitorArrayIndex]['datetime_added'] = Carbon::parse($this->exhibitorDateTime)->format('M j, Y g:i A');
        $this->finalListOfExhibitorsConst[$this->exhibitorArrayIndex]['datetime_added'] = Carbon::parse($this->exhibitorDateTime)->format('M j, Y g:i A');

        $this->resetEditExhibitorDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media Partner Datetime updated successfully!',
            'text' => ''
        ]);
    }
}
