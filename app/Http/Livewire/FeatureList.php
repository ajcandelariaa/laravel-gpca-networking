<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Livewire\Component;

class FeatureList extends Component
{
    public $event;

    public $finalListOfFeatures = array(), $finalListOfFeaturesConst = array();

    public $addFeatureForm, $name, $short_name, $location, $link, $start_date, $end_date;

    public $featureId, $featureDateTime, $featureArrayIndex, $editFeatureDateTimeForm;

    protected $listeners = ['addFeatureConfirmed' => 'addFeature'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $features = Features::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($features->isNotEmpty()) {
            foreach ($features as $feature) {
                $formattedDate =  Carbon::parse($feature->start_date)->format('d M Y') . ' - ' . Carbon::parse($feature->end_date)->format('d M Y');
                array_push($this->finalListOfFeatures, [
                    'id' => $feature->id,
                    'name' => $feature->name,
                    'short_name' => $feature->short_name,
                    'location' => $feature->location,
                    'date' => $formattedDate,
                    'active' => $feature->active,
                    'datetime_added' => Carbon::parse($feature->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfFeaturesConst = $this->finalListOfFeatures;
        }

        $this->addFeatureForm = false;
    }

    public function render()
    {
        return view('livewire.event.features.feature-list');
    }

    public function showAddFeature()
    {
        $this->addFeatureForm = true;
    }

    public function addFeatureConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'short_name' => 'required',
            'location' => 'required',
            'link' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addFeatureConfirmed",
        ]);
    }

    public function cancelAddFeature()
    {
        $this->resetAddFeatureFields();
    }

    public function resetAddFeatureFields()
    {
        $this->addFeatureForm = false;
        $this->name = null;
        $this->short_name = null;
        $this->link = null;
        $this->location = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function addFeature()
    {
        $newFeature = Features::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'location' => $this->location,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'datetime_added' => Carbon::now(),
        ]);

        $formattedDate =  Carbon::parse($this->start_date)->format('d M Y') . ' - ' . Carbon::parse($this->end_date)->format('d M Y');

        array_push($this->finalListOfFeatures, [
            'id' => $newFeature->id,
            'name' => $this->name,
            'short_name' => $this->short_name,
            'location' => $this->location,
            'date' => $formattedDate,
            'active' => true,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->finalListOfFeaturesConst = $this->finalListOfFeatures;

        $this->resetAddFeatureFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Feature added successfully!',
            'text' => ''
        ]);
    }


    public function updateFeatureStatus($arrayIndex, $featureId, $status)
    {
        if ($status) {
            $newStatus = false;
        } else {
            $newStatus = true;
        }

        Features::where('id', $featureId)->update([
            'active' => $newStatus,
        ]);

        $this->finalListOfFeatures[$arrayIndex]['active'] = $newStatus;
        $this->finalListOfFeaturesConst[$arrayIndex]['active'] = $newStatus;
    }

    // EDIT DATETIME
    public function showEditFeatureDateTime($featureId, $featureArrayIndex)
    {
        $featureDateTime = Features::where('id', $featureId)->value('datetime_added');

        $this->featureId = $featureId;
        $this->featureDateTime = $featureDateTime;
        $this->featureArrayIndex = $featureArrayIndex;
        $this->editFeatureDateTimeForm = true;
    }

    public function cancelEditFeatureDateTime()
    {
        $this->resetEditFeatureDateTimeFields();
    }

    public function resetEditFeatureDateTimeFields()
    {
        $this->editFeatureDateTimeForm = false;
        $this->featureId = null;
        $this->featureDateTime = null;
        $this->featureArrayIndex = null;
    }
    
    public function editFeatureDateTime()
    {
        $this->validate([
            'featureDateTime' => 'required',
        ]);

        Features::where('id', $this->featureId)->update([
            'datetime_added' => $this->featureDateTime,
        ]);

        $this->finalListOfFeatures[$this->featureArrayIndex]['datetime_added'] = Carbon::parse($this->featureDateTime)->format('M j, Y g:i A');
        $this->finalListOfFeaturesConst[$this->featureArrayIndex]['datetime_added'] = Carbon::parse($this->featureDateTime)->format('M j, Y g:i A');

        $this->resetEditFeatureDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Feature Datetime updated successfully!',
            'text' => ''
        ]);
    }
}
