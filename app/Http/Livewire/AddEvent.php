<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use App\Models\Event as Events;
use App\Models\Media as Medias;
use Carbon\Carbon;
use DateTimeZone;
use Livewire\Component;

class AddEvent extends Component
{
    public $eventCategories;

    public $category, $full_name, $short_name, $edition, $location, $event_full_link, $event_short_link, $event_start_date, $event_end_date, $event_logo_media_id, $event_logo_placeholder_text;
    public $primary_bg_color, $secondary_bg_color, $primary_text_color, $secondary_text_color;
    public $timezone, $timezoneChoices;

    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage;

    protected $listeners = ['addEventConfirmed' => 'addEvent'];

    public function mount()
    {
        $this->eventCategories = config('app.eventCategories');
        $this->mediaFileList = array();
        $this->chooseImageModal = false;
        $this->timezoneChoices = DateTimeZone::listIdentifiers();
    }

    public function render()
    {
        return view('livewire.home.add_event.add-event');
    }

    public function addEventConfirmation()
    {
        $this->validate([
            'category' => 'required',
            'full_name' => 'required',
            'short_name' => 'required',
            'edition' => 'required',
            'location' => 'required',
            'event_full_link' => 'required',
            'event_short_link' => 'required',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date',

            'timezone' => 'required',

            'event_logo_placeholder_text' => 'required',

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
            'livewireEmit' => "addEventConfirmed",
        ]);
    }

    public function addEvent()
    {
        $event = Events::create([
            'category' => $this->category,
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'edition' => $this->edition,
            'location' => $this->location,
            'event_full_link' => $this->event_full_link,
            'event_short_link' => $this->event_short_link,
            'event_start_date' => $this->event_start_date,
            'event_end_date' => $this->event_end_date,

            'timezone' => $this->timezone,

            'event_logo_media_id' => $this->event_logo_media_id,

            'primary_bg_color' => $this->primary_bg_color,
            'secondary_bg_color' => $this->secondary_bg_color,
            'primary_text_color' => $this->primary_text_color,
            'secondary_text_color' => $this->secondary_text_color,

            'year' => strval(Carbon::parse($this->event_start_date)->year),
        ]);

        mediaUsageUpdate(
            MediaUsageUpdateTypes::ADD_ONLY->value,
            $this->event_logo_media_id,
            MediaEntityTypes::EVENT_LOGO->value,
            $event->id,
        );

        return redirect()->route('admin.events.view')->with('success', 'Event added successfully.');
    }

    public function chooseEventLogo()
    {
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
        $this->event_logo_media_id = $this->activeSelectedImage['id'];
        $this->event_logo_placeholder_text = $this->activeSelectedImage['file_name'];
        $this->activeSelectedImage = null;
        $this->chooseImageModal = false;
    }

    public function cancelChooseImage()
    {
        $this->event_logo_media_id = null;
        $this->event_logo_placeholder_text = null;
        $this->activeSelectedImage = null;
        $this->mediaFileList = array();
        $this->chooseImageModal = false;
    }
}
