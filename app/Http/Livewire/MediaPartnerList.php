<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use App\Models\Event as Events;
use App\Models\Media as Medias;
use App\Models\MediaPartner as MediaPartners;
use Carbon\Carbon;
use Livewire\Component;

class MediaPartnerList extends Component
{
    public $event;
    public $finalListOfMediaPartners = array();

    // EDIT DETAILS
    public $name, $website;
    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage, $image_media_id, $image_placeholder_text;
    public $addMediaPartnerForm;

    // EDIT DATE TIME
    public $mediaPartnerId, $mediaPartnerDateTime, $mediaPartnerArrayIndex;
    public $inputNameVariableDateTime, $btnUpdateNameMethodDateTime, $btnCancelNameMethodDateTime;
    public $editMediaPartnerDateTimeForm;

    // DELETE
    public $activeDeleteIndex;

    protected $listeners = ['addMediaPartnerConfirmed' => 'addMediaPartner', 'deleteMediaPartnerConfirmed' => 'deleteMediaParnter'];

    public function mount($eventId, $eventCategory)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();

        $mediaPartners = MediaPartners::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();
        if ($mediaPartners->isNotEmpty()) {
            foreach ($mediaPartners as $mediaPartner) {
                array_push($this->finalListOfMediaPartners, [
                    'id' => $mediaPartner->id,
                    'name' => $mediaPartner->name,
                    'website' => $mediaPartner->website,
                    'is_active' => $mediaPartner->is_active,
                    'logo' => Medias::where('id', $mediaPartner->logo_media_id)->value('file_url'),
                    'datetime_added' => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
                ]);
            }
        }

        $this->inputNameVariableDateTime = "mediaPartnerDateTime";
        $this->btnUpdateNameMethodDateTime = "editMediaPartnerDateTime";
        $this->btnCancelNameMethodDateTime = "resetEditMediaPartnerDateTimeFields";

        $this->addMediaPartnerForm = false;
        $this->editMediaPartnerDateTimeForm = false;

        $this->mediaFileList = getMediaFileList();
        $this->chooseImageModal = false;
    }

    public function render()
    {
        return view('livewire.event.media-partners.media-partner-list');
    }

    public function showAddMediaPartner()
    {
        $this->addMediaPartnerForm = true;
    }

    public function resetAddMediaPartnerFields()
    {
        $this->addMediaPartnerForm = false;
        $this->name = null;
        $this->website = null;
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
    }

    public function addMediaPartnerConfirmation()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, add it!",
            'livewireEmit' => "addMediaPartnerConfirmed",
        ]);
    }

    public function addMediaPartner()
    {
        $newMediaPartner = MediaPartners::create([
            'event_id' => $this->event->id,
            'name' => $this->name,
            'website' => $this->website,
            'logo_media_id' => $this->image_media_id ?? null,
            'datetime_added' => Carbon::now(),
        ]);

        if ($this->image_media_id) {
            mediaUsageUpdate(
                MediaUsageUpdateTypes::ADD_ONLY->value,
                $this->image_media_id,
                MediaEntityTypes::MEDIA_PARTNER_LOGO->value,
                $newMediaPartner->id,
            );
        }

        array_push($this->finalListOfMediaPartners, [
            'id' => $newMediaPartner->id,
            'name' => $this->name,
            'website' => $this->website,
            'is_active' => true,
            'logo' => $this->image_media_id ? Medias::where('id', $this->image_media_id)->value('file_url') : null,
            'datetime_added' => Carbon::parse(Carbon::now())->format('M j, Y g:i A'),
        ]);

        $this->resetAddMediaPartnerFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media partner added successfully!',
            'text' => ''
        ]);
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



    public function updateMediaPartnerStatus($arrayIndex)
    {
        MediaPartners::where('id', $this->finalListOfMediaPartners[$arrayIndex]['id'])->update([
            'is_active' => !$this->finalListOfMediaPartners[$arrayIndex]['is_active'],
        ]);

        $this->finalListOfMediaPartners[$arrayIndex]['is_active'] = !$this->finalListOfMediaPartners[$arrayIndex]['is_active'];
    }



    // EDIT DATETIME
    public function showEditMediaPartnerDateTime($mediaPartnerId, $mediaPartnerArrayIndex)
    {
        $mediaPartnerDateTime = MediaPartners::where('id', $mediaPartnerId)->value('datetime_added');

        $this->mediaPartnerId = $mediaPartnerId;
        $this->mediaPartnerDateTime = $mediaPartnerDateTime;
        $this->mediaPartnerArrayIndex = $mediaPartnerArrayIndex;
        $this->editMediaPartnerDateTimeForm = true;
    }

    public function resetEditMediaPartnerDateTimeFields()
    {
        $this->editMediaPartnerDateTimeForm = false;
        $this->mediaPartnerId = null;
        $this->mediaPartnerDateTime = null;
        $this->mediaPartnerArrayIndex = null;
    }

    public function editMediaPartnerDateTime()
    {
        $this->validate([
            'mediaPartnerDateTime' => 'required',
        ]);

        MediaPartners::where('id', $this->mediaPartnerId)->update([
            'datetime_added' => $this->mediaPartnerDateTime,
        ]);

        $this->finalListOfMediaPartners[$this->mediaPartnerArrayIndex]['datetime_added'] = Carbon::parse($this->mediaPartnerDateTime)->format('M j, Y g:i A');

        $this->resetEditMediaPartnerDateTimeFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media Partner Datetime updated successfully!',
            'text' => ''
        ]);
    }

    public function deleteMediaParnterConfirmation($index)
    {
        $this->activeDeleteIndex = $index;
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to delete?',
            'text' => "",
            'buttonConfirmText' => "Yes, delete it!",
            'livewireEmit' => "deleteMediaPartnerConfirmed",
        ]);
    }

    public function deleteMediaParnter()
    {
        $mediaPartner = MediaPartners::where('id', $this->finalListOfMediaPartners[$this->activeDeleteIndex]['id'])->first();

        if($mediaPartner){
            if($mediaPartner->logo_media_id){
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_ONLY->value,
                    $mediaPartner->logo_media_id,
                    MediaEntityTypes::MEDIA_PARTNER_LOGO->value,
                    $mediaPartner->id,
                    getMediaUsageId($mediaPartner->logo_media_id, MediaEntityTypes::MEDIA_PARTNER_LOGO->value, $mediaPartner->id),
                );
            }

            if($mediaPartner->banner_media_id){
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_ONLY->value,
                    $mediaPartner->banner_media_id,
                    MediaEntityTypes::MEDIA_PARTNER_BANNER->value,
                    $mediaPartner->id,
                    getMediaUsageId($mediaPartner->banner_media_id, MediaEntityTypes::MEDIA_PARTNER_BANNER->value, $mediaPartner->id),
                );
            }

            $mediaPartner->delete();

            unset($this->finalListOfMediaPartners[$this->activeDeleteIndex]);
            $this->finalListOfMediaPartners = array_values($this->finalListOfMediaPartners);
        }
        
        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media Partner deleted successfully!',
            'text' => ''
        ]);
    }
}
