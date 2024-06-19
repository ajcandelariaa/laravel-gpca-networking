<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Media as Medias;
use App\Models\MeetingRoomPartner as MeetingRoomPartners;
use Illuminate\Support\Facades\Storage;

class MeetingRoomPartnerDetails extends Component
{
    public $event, $meetingRoomPartnerData;

    public $assetType, $editMeetingRoomPartnerAssetForm, $image_media_id, $image_placeholder_text;
    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage;

    public $name, $profile_html_text, $location, $country, $contact_person_name, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram;
    public $editMeetingRoomPartnerDetailsForm;

    protected $listeners = ['editMeetingRoomPartnerDetailsConfirmed' => 'editMeetingRoomPartnerDetails', 'editMeetingRoomPartnerAssetConfirmed' => 'editMeetingRoomPartnerAsset'];

    public function mount($eventId, $eventCategory, $meetingRoomPartnerData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->meetingRoomPartnerData = $meetingRoomPartnerData;
        $this->mediaFileList = getMediaFileList();
        $this->editMeetingRoomPartnerAssetForm = false;
        $this->editMeetingRoomPartnerDetailsForm = false;

    }

    public function render()
    {
        return view('livewire.event.meeting-room-partners.meeting-room-partner-details');
    }





    // EDIT MEETING ROOM PARTNER ASSET
    public function showEditMeetingRoomPartnerAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editMeetingRoomPartnerAssetForm = true;
    }

    public function resetEditMeetingRoomPartnerAssetFields()
    {
        $this->editMeetingRoomPartnerAssetForm = false;
        $this->assetType = null;
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
    }

    public function editMeetingRoomPartnerAssetConfirmation()
    {
        
        $this->validate([
            'image_placeholder_text' => 'required'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editMeetingRoomPartnerAssetConfirmed",
        ]);
    }

    public function editMeetingRoomPartnerAsset()
    {
        if ($this->assetType == "Meeting room partner logo") {
            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'logo_media_id' => $this->image_media_id,
            ]);

            if ($this->meetingRoomPartnerData['logo']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::MEETING_ROOM_PARTNER_LOGO->value,
                    $this->meetingRoomPartnerData['meetingRoomPartnerId'],
                    $this->meetingRoomPartnerData['logo']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::MEETING_ROOM_PARTNER_LOGO->value,
                    $this->meetingRoomPartnerData['meetingRoomPartnerId'],
                    $this->meetingRoomPartnerData['logo']['media_usage_id']
                );
            }

            $this->meetingRoomPartnerData['logo'] = [
                'media_id' => $this->image_media_id,
                'media_usage_id' => getMediaUsageId($this->image_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_LOGO->value, $this->meetingRoomPartnerData['meetingRoomPartnerId']),
                'url' => Medias::where('id', $this->image_media_id)->value('file_url'),
            ];
        } else {
            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'banner_media_id' => $this->image_media_id,
            ]);

            if ($this->meetingRoomPartnerData['banner']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::MEETING_ROOM_PARTNER_BANNER->value,
                    $this->meetingRoomPartnerData['meetingRoomPartnerId'],
                    $this->meetingRoomPartnerData['banner']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::MEETING_ROOM_PARTNER_BANNER->value,
                    $this->meetingRoomPartnerData['meetingRoomPartnerId'],
                    $this->meetingRoomPartnerData['banner']['media_usage_id']
                );
            }

            $this->meetingRoomPartnerData['banner'] = [
                'media_id' => $this->image_media_id,
                'media_usage_id' => getMediaUsageId($this->image_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_BANNER->value, $this->meetingRoomPartnerData['meetingRoomPartnerId']),
                'url' => Medias::where('id', $this->image_media_id)->value('file_url'),
            ];
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditMeetingRoomPartnerAssetFields();
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



    // EDIT MEETING ROOM PARTNER DETAILS
    public function showEditMeetingRoomPartnerDetails()
    {
        $this->name = $this->meetingRoomPartnerData['name'];
        $this->profile_html_text = $this->meetingRoomPartnerData['profile_html_text'];
        $this->location = $this->meetingRoomPartnerData['location'];
        
        $this->country = $this->meetingRoomPartnerData['country'];
        $this->contact_person_name = $this->meetingRoomPartnerData['contact_person_name'];
        $this->email_address = $this->meetingRoomPartnerData['email_address'];
        $this->mobile_number = $this->meetingRoomPartnerData['mobile_number'];

        $this->website = $this->meetingRoomPartnerData['website'];
        $this->facebook = $this->meetingRoomPartnerData['facebook'];
        $this->linkedin = $this->meetingRoomPartnerData['linkedin'];
        $this->twitter = $this->meetingRoomPartnerData['twitter'];
        $this->instagram = $this->meetingRoomPartnerData['instagram'];
        $this->editMeetingRoomPartnerDetailsForm = true;
    }

    public function resetEditMeetingRoomPartnerDetailsFields()
    {
        $this->editMeetingRoomPartnerDetailsForm = false;
        $this->name = null;
        $this->profile_html_text = null;
        $this->location = null;

        $this->country = null;
        $this->contact_person_name = null;
        $this->email_address = null;
        $this->mobile_number = null;
        $this->website = null;
        $this->facebook = null;
        $this->linkedin = null;
        $this->twitter = null;
        $this->instagram = null;
    }

    public function editMeetingRoomPartnerDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editMeetingRoomPartnerDetailsConfirmed",
        ]);
    }

    public function editMeetingRoomPartnerDetails()
    {
        MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
            'name' => $this->name,
            'profile_html_text' => $this->profile_html_text,
            'location' => $this->location,

            'country' => $this->country == "" ? null : $this->country,
            'contact_person_name' => $this->contact_person_name == "" ? null : $this->contact_person_name,
            'email_address' => $this->email_address == "" ? null : $this->email_address,
            'mobile_number' => $this->mobile_number == "" ? null : $this->mobile_number,
            'website' => $this->website == "" ? null : $this->website,
            'facebook' => $this->facebook == "" ? null : $this->facebook,
            'linkedin' => $this->linkedin == "" ? null : $this->linkedin,
            'twitter' => $this->twitter == "" ? null : $this->twitter,
            'instagram' => $this->instagram == "" ? null : $this->instagram,
        ]);

        $this->meetingRoomPartnerData['name'] = $this->name;
        $this->meetingRoomPartnerData['profile_html_text'] = $this->profile_html_text;
        $this->meetingRoomPartnerData['location'] = $this->location;
        
        $this->meetingRoomPartnerData['country'] = $this->country;
        $this->meetingRoomPartnerData['contact_person_name'] = $this->contact_person_name;
        $this->meetingRoomPartnerData['email_address'] = $this->email_address;
        $this->meetingRoomPartnerData['mobile_number'] = $this->mobile_number;
        $this->meetingRoomPartnerData['website'] = $this->website;
        $this->meetingRoomPartnerData['facebook'] = $this->facebook;
        $this->meetingRoomPartnerData['linkedin'] = $this->linkedin;
        $this->meetingRoomPartnerData['twitter'] = $this->twitter;
        $this->meetingRoomPartnerData['instagram'] = $this->instagram;

        $this->resetEditMeetingRoomPartnerDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Meeting room partner details updated succesfully!',
            'text' => "",
        ]);
    }
}
