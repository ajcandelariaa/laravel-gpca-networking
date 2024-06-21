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

    public $full_name, $short_name, $location, $link, $start_date, $end_date;
    public $primary_bg_color, $secondary_bg_color, $primary_text_color, $secondary_text_color;
    public $addFeatureForm;

    public $featureId, $featureDateTime, $featureArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editFeatureDateTimeForm;

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
                    'full_name' => $feature->full_name,
                    'date' => $formattedDate,
                    'is_active' => $feature->is_active,
                    'datetime_added' => Carbon::parse($feature->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
            $this->finalListOfFeaturesConst = $this->finalListOfFeatures;
        }

        $this->inputNameVariableDateTime = "featureDateTime";
        $this->btnUpdateNameMethodDateTime = "editFeatureDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditFeatureDateTimeFields";

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
            'full_name' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',

            'primary_bg_color' => 'required',
            'secondary_bg_color' => 'required',
            'primary_text_color' => 'required',
            'secondary_text_color' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addFeatureConfirmed",
        ]);
    }

    public function resetAddFeatureFields()
    {
        $this->addFeatureForm = false;
        $this->full_name = null;
        $this->start_date = null;
        $this->end_date = null;
        $this->primary_bg_color = null;
        $this->secondary_bg_color = null;
        $this->primary_text_color = null;
        $this->secondary_text_color= null;
    }

    public function addFeature()
    {
        $newFeature = Features::create([
            'event_id' => $this->event->id,
            'full_name' => $this->full_name,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,

            'primary_bg_color' => $this->primary_bg_color,
            'secondary_bg_color' => $this->secondary_bg_color,
            'primary_text_color' => $this->primary_text_color,
            'secondary_text_color' => $this->secondary_text_color,
            'datetime_added' => Carbon::now(),
        ]);

        $formattedDate =  Carbon::parse($this->start_date)->format('d M Y') . ' - ' . Carbon::parse($this->end_date)->format('d M Y');

        array_push($this->finalListOfFeatures, [
            'id' => $newFeature->id,
            'full_name' => $this->full_name,
            'date' => $formattedDate,
            'is_active' => true,
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


    public function updateFeatureStatus($arrayIndex)
    {
        Features::where('id', $this->finalListOfFeatures[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfFeatures[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfFeatures[$arrayIndex]['is_active'] = !$this->finalListOfFeatures[$arrayIndex]['is_active'];
        $this->finalListOfFeaturesConst[$arrayIndex]['is_active'] = !$this->finalListOfFeaturesConst[$arrayIndex]['is_active'];
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
