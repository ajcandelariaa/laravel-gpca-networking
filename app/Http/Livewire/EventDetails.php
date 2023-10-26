<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventDetails extends Component
{
    use WithFileUploads;

    public $eventData, $eventCategories;

    public $name, $category, $location, $description, $event_full_link, $event_short_link, $event_start_date, $event_end_date, $editEventDetailsForm;

    public $assetType, $image, $editEventAssetForm;

    protected $listeners = ['editEventDetailsConfirmed' => 'editEventDetails', 'editEventAssetConfirmed' => 'editEventAsset'];

    public function mount($eventData)
    {
        $this->eventCategories = config('app.eventCategories');
        $this->eventData = $eventData;
        $this->editEventAssetForm = false;
        $this->editEventDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.details.event-details');
    }

    // EDIT EVENT DETAILS
    public function showEditEventDetails()
    {
        $this->name = $this->eventData['eventDetails']['name'];
        $this->category = $this->eventData['eventDetails']['category'];
        $this->location = $this->eventData['eventDetails']['location'];
        $this->description = $this->eventData['eventDetails']['description'];

        $this->event_full_link = $this->eventData['eventDetails']['event_full_link'];
        $this->event_short_link = $this->eventData['eventDetails']['event_short_link'];

        $this->event_start_date = $this->eventData['eventDetails']['event_start_date'];
        $this->event_end_date = $this->eventData['eventDetails']['event_end_date'];

        $this->editEventDetailsForm = true;
    }

    public function cancelEditEventDetails()
    {
        $this->resetEditEventDetailsFields();
    }

    public function resetEditEventDetailsFields()
    {
        $this->name = null;
        $this->category = null;
        $this->location = null;
        $this->description = null;
        $this->event_full_link = null;
        $this->event_short_link = null;
        $this->event_start_date = null;
        $this->event_end_date = null;
        $this->editEventDetailsForm = false;
    }

    public function editEventDetailsConfirmation()
    {
        $this->validate([
            'category' => 'required',
            'name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'event_full_link' => 'required',
            'event_short_link' => 'required',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date',
        ]);


        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editEventDetailsConfirmed",
        ]);
    }

    public function editEventDetails()
    {
        $currentYear = strval(Carbon::parse($this->event_start_date)->year);

        Events::where('id', $this->eventData['eventId'])->update([
            'category' => $this->category,
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'event_full_link' => $this->event_full_link,
            'event_short_link' => $this->event_short_link,
            'event_start_date' => $this->event_start_date,
            'event_end_date' => $this->event_end_date,
            'year' => $currentYear,
        ]);

        if($this->category == $this->eventData['eventCategory']){
            $this->dispatchBrowserEvent('swal:success', [
                'type' => 'success',
                'message' => 'Event details updated succesfully!',
                'text' => "",
            ]);
    
            $this->resetEditEventDetailsFields();
        } else {
            return redirect()->route('admin.event.details.view', ['eventCategory' => $this->category, 'eventId' => $this->eventData['eventId']]);
        }
    }


    // EDIT EVENT ASSET
    public function showEditEventAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editEventAssetForm = true;
    }

    public function cancelEditEventAsset()
    {
        $this->resetEditEventAssetFields();
    }

    public function resetEditEventAssetFields()
    {
        $this->assetType = null;
        $this->image = null;
        $this->editEventAssetForm = false;
    }

    public function editEventAssetConfirmation()
    {
        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editEventAssetConfirmed",
        ]);
    }

    public function editEventAsset()
    {
        $currentYear = strval(Carbon::parse($this->eventData['eventDetails']['event_start_date'])->year);
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Event Logo") {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/logo', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'event_logo' => $path,
            ]);
            $this->eventData['eventAssets']['event_logo'] = Storage::url($path);
        } else if ($this->assetType == 'Event Logo inverted') {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/logo', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'event_logo_inverted' => $path,
            ]);
            $this->eventData['eventAssets']['event_logo_inverted'] = Storage::url($path);
        } else if ($this->assetType == 'App Sponsor logo') {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/logo', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'app_sponsor_logo' => $path,
            ]);
            $this->eventData['eventAssets']['app_sponsor_logo'] = Storage::url($path);
        } else if ($this->assetType == 'Event Banner') {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/banner', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'event_banner' => $path,
            ]);
            $this->eventData['eventAssets']['event_banner'] = Storage::url($path);
        } else if ($this->assetType == 'App Sponsor banner') {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/banner', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'app_sponsor_banner' => $path,
            ]);
            $this->eventData['eventAssets']['app_sponsor_banner'] = Storage::url($path);
        } else {
            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->eventData['eventCategory'] . '/details/splash-screen', $fileName);
            Events::where('id', $this->eventData['eventId'])->update([
                'event_splash_screen' => $path,
            ]);
            $this->eventData['eventAssets']['event_splash_screen'] = Storage::url($path);
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditEventAssetFields();
    }
}
