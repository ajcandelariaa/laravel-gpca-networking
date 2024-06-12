<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use App\Models\Event as Events;
use App\Models\Media as Medias;
use Carbon\Carbon;
use Livewire\Component;

class EventDetails extends Component
{
    public $eventData, $eventCategories;

    public $full_name, $short_name, $category, $edition, $location, $description_html_text, $event_full_link, $event_short_link, $event_start_date, $event_end_date, $editEventDetailsForm;

    public $primary_bg_color, $secondary_bg_color, $primary_text_color, $secondary_text_color, $editEventColorsForm;

    // EDIT ASSETS
    public $assetType, $editEventAssetForm, $image_media_id, $image_placeholder_text;
    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage;

    protected $listeners = ['editEventDetailsConfirmed' => 'editEventDetails', 'editEventColorsConfirmed' => 'editEventColors', 'editEventAssetConfirmed' => 'editEventAsset'];

    public function mount($eventData)
    {
        $this->eventCategories = config('app.eventCategories');
        $this->eventData = $eventData;

        $mediaFileListTemp = Medias::orderBy('date_uploaded', 'DESC')->get();
        if ($mediaFileListTemp->isNotEmpty()) {
            foreach ($mediaFileListTemp as $mediaFile) {
                array_push($this->mediaFileList, [
                    'id' => $mediaFile->id,
                    'file_url' => $mediaFile->file_url,
                    'file_directory' => $mediaFile->file_directory,
                    'file_name' => $mediaFile->file_name,
                    'file_type' => $mediaFile->file_type,
                    'file_size' => $mediaFile->file_size,
                    'width' => $mediaFile->width,
                    'height' => $mediaFile->height,
                    'date_uploaded' => $mediaFile->date_uploaded,
                ]);
            }
        }
        $this->editEventAssetForm = false;
        $this->editEventDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.details.event-details');
    }


    // TOGGLE VISIBILITY AND ACCESSIBILITY
    public function toggleVisibilityInTheApp(){
        
        Events::where('id', $this->eventData['eventId'])->update([
            'is_visible_in_the_app' => !$this->eventData['eventDetails']['is_visible_in_the_app'],
        ]);
        $this->eventData['eventDetails']['is_visible_in_the_app'] = !$this->eventData['eventDetails']['is_visible_in_the_app'];
    }

    public function toggleAccessibilityInTheApp(){
        Events::where('id', $this->eventData['eventId'])->update([
            'is_accessible_in_the_app' => !$this->eventData['eventDetails']['is_accessible_in_the_app'],
        ]);
        $this->eventData['eventDetails']['is_accessible_in_the_app'] = !$this->eventData['eventDetails']['is_accessible_in_the_app'];
    }


    // EDIT EVENT DETAILS
    public function showEditEventDetails()
    {
        $this->full_name = $this->eventData['eventDetails']['full_name'];
        $this->short_name = $this->eventData['eventDetails']['short_name'];
        $this->category = $this->eventData['eventDetails']['category'];
        $this->edition = $this->eventData['eventDetails']['edition'];
        $this->location = $this->eventData['eventDetails']['location'];
        $this->description_html_text = $this->eventData['eventDetails']['description_html_text'];

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
        $this->full_name = null;
        $this->short_name = null;
        $this->category = null;
        $this->edition = null;
        $this->location = null;
        $this->description_html_text = null;
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
            'full_name' => 'required',
            'short_name' => 'required',
            'location' => 'required',
            'edition' => 'required',
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
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'location' => $this->location,
            'edition' => $this->edition,
            'description_html_text' => $this->description_html_text,
            'event_full_link' => $this->event_full_link,
            'event_short_link' => $this->event_short_link,
            'event_start_date' => $this->event_start_date,
            'event_end_date' => $this->event_end_date,
            'year' => $currentYear,
        ]);

        $this->eventData['category'] = $this->category;
        $this->eventData['eventDetails']['full_name'] = $this->full_name;
        $this->eventData['eventDetails']['short_name'] = $this->short_name;
        $this->eventData['eventDetails']['category'] = $this->category;
        $this->eventData['eventDetails']['location'] = $this->location;
        $this->eventData['eventDetails']['edition'] = $this->edition;
        $this->eventData['eventDetails']['description_html_text'] = $this->description_html_text;
        $this->eventData['eventDetails']['event_full_link'] = $this->event_full_link;
        $this->eventData['eventDetails']['event_short_link'] = $this->event_short_link;
        $this->eventData['eventDetails']['event_short_link'] = $this->event_short_link;
        $this->eventData['eventDetails']['event_start_date'] = $this->event_start_date;
        $this->eventData['eventDetails']['event_end_date'] = $this->event_end_date;
        $this->eventData['eventDetails']['finalEventStartDate'] = Carbon::parse($this->event_start_date)->format('d M Y');
        $this->eventData['eventDetails']['finalEventEndDate'] = Carbon::parse($this->event_end_date)->format('d M Y');
        $this->eventData['eventDetails']['year'] = $currentYear;

        if ($this->category == $this->eventData['eventCategory']) {
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


    // EDIT EVENT COLORS
    public function showEditEventColors()
    {
        $this->primary_bg_color = $this->eventData['eventColors']['primary_bg_color'];
        $this->secondary_bg_color = $this->eventData['eventColors']['secondary_bg_color'];

        $this->primary_text_color = $this->eventData['eventColors']['primary_text_color'];
        $this->secondary_text_color = $this->eventData['eventColors']['secondary_text_color'];

        $this->editEventColorsForm = true;
    }

    public function cancelEditEventColors()
    {
        $this->resetEditEventColorsFields();
    }

    public function resetEditEventColorsFields()
    {
        $this->primary_bg_color = null;
        $this->secondary_bg_color = null;
        $this->primary_text_color = null;
        $this->secondary_text_color = null;
        $this->editEventColorsForm = false;
    }

    public function editEventColorsConfirmation()
    {
        $this->validate([
            'primary_bg_color' => 'required',
            'secondary_bg_color' => 'required',
            'primary_text_color' => 'required',
            'secondary_text_color' => 'required',
        ]);


        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editEventColorsConfirmed",
        ]);
    }

    public function editEventColors()
    {
        Events::where('id', $this->eventData['eventId'])->update([
            'primary_bg_color' => $this->primary_bg_color,
            'secondary_bg_color' => $this->secondary_bg_color,
            'primary_text_color' => $this->primary_text_color,
            'secondary_text_color' => $this->secondary_text_color,
        ]);

        $this->eventData['eventColors']['primary_bg_color'] = $this->primary_bg_color;
        $this->eventData['eventColors']['secondary_bg_color'] = $this->secondary_bg_color;
        $this->eventData['eventColors']['primary_text_color'] = $this->primary_text_color;
        $this->eventData['eventColors']['secondary_text_color'] = $this->secondary_text_color;

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Event colors updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditEventColorsFields();
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
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
        $this->editEventAssetForm = false;
    }

    public function editEventAssetConfirmation()
    {
        $this->validate([
            'image_placeholder_text' => 'required'
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
        if ($this->assetType == "Event Logo") {
            Events::where('id', $this->eventData['eventId'])->update([
                'event_logo_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['event_logo']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_LOGO->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_logo']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_LOGO->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_logo']['media_usage_id']
                );
            }
        } else if ($this->assetType == 'Event Logo inverted') {
            Events::where('id', $this->eventData['eventId'])->update([
                'event_logo_inverted_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['event_logo_inverted']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_LOGO_INVERTED->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_logo_inverted']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_LOGO_INVERTED->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_logo_inverted']['media_usage_id']
                );
            }
        } else if ($this->assetType == 'App Sponsor logo') {
            Events::where('id', $this->eventData['eventId'])->update([
                'app_sponsor_logo_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['app_sponsor_logo']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_APP_SPONSOR_LOGO->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['app_sponsor_logo']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_APP_SPONSOR_LOGO->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['app_sponsor_logo']['media_usage_id']
                );
            }
        } else if ($this->assetType == 'Event Banner') {
            Events::where('id', $this->eventData['eventId'])->update([
                'event_banner_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['event_banner']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_BANNER->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_banner']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_BANNER->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_banner']['media_usage_id']
                );
            }
        } else if ($this->assetType == 'App Sponsor banner') {
            Events::where('id', $this->eventData['eventId'])->update([
                'app_sponsor_banner_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['app_sponsor_banner']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_APP_SPONSOR_BANNER->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['app_sponsor_banner']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_APP_SPONSOR_BANNER->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['app_sponsor_banner']['media_usage_id']
                );
            }
        } else {
            Events::where('id', $this->eventData['eventId'])->update([
                'event_splash_screen_media_id' => $this->image_media_id,
            ]);

            if ($this->eventData['eventAssets']['event_splash_screen']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_SPLASH_SCREEN->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_splash_screen']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::EVENT_SPLASH_SCREEN->value,
                    $this->eventData['eventId'],
                    $this->eventData['eventAssets']['event_splash_screen']['media_usage_id']
                );
            }
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditEventAssetFields();
    }


    // FOR CHOOSING IMAGE MODAL
    public function chooseImage()
    {
        $this->chooseImageModal = true;
    }

    public function showMediaFileDetails($arrayIndex)
    {
        $this->activeSelectedImage = $this->mediaFileList[$arrayIndex];
    }

    public function unshowMediaFileDetails()
    {
        $this->activeSelectedImage = array();
    }

    public function selectChooseImage()
    {
        $this->image_media_id = $this->activeSelectedImage['id'];
        $this->image_placeholder_text = $this->activeSelectedImage['file_name'];
        $this->activeSelectedImage = null;
        $this->chooseImageModal = false;
    }

    public function cancelChooseImage()
    {
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
        $this->activeSelectedImage = null;
        $this->chooseImageModal = false;
    }
}
