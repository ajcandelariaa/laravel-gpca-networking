<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\MeetingRoomPartner as MeetingRoomPartners;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class MeetingRoomPartnerDetails extends Component
{
    use WithFileUploads;

    public $event, $meetingRoomPartnerData;

    public $image, $assetType, $editMeetingRoomPartnerAssetForm, $imageDefault;

    public $name, $profile, $location, $country, $contact_person_name, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram, $editMeetingRoomPartnerDetailsForm;

    protected $listeners = ['editMeetingRoomPartnerDetailsConfirmed' => 'editMeetingRoomPartnerDetails', 'editMeetingRoomPartnerAssetConfirmed' => 'editMeetingRoomPartnerAsset', 'removeMeetingRoomPartnerAssetConfirmed' => 'removeMeetingRoomPartnerAsset'];

    public function mount($eventId, $eventCategory, $meetingRoomPartnerData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->meetingRoomPartnerData = $meetingRoomPartnerData;

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

    public function cancelEditMeetingRoomPartnerAsset()
    {
        $this->resetEditMeetingRoomPartnerAssetFields();
    }

    public function resetEditMeetingRoomPartnerAssetFields()
    {
        $this->editMeetingRoomPartnerAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editMeetingRoomPartnerAssetConfirmation()
    {
        
        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
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
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Meeting room partner logo") {

            if(!$this->meetingRoomPartnerData['meetingRoomPartnerLogoDefault']){
                $meetingRoomPartnerAssetUrl = MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->value('logo');
                if($meetingRoomPartnerAssetUrl){
                    $this->removeMeetingRoomPartnerAssetInStorage($meetingRoomPartnerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/meeting-room-partners/logo', $fileName);

            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'logo' => $path,
            ]);

            $this->meetingRoomPartnerData['meetingRoomPartnerLogo'] = Storage::url($path);
            $this->meetingRoomPartnerData['meetingRoomPartnerLogoDefault'] = false;
        } else {
            
            if(!$this->meetingRoomPartnerData['meetingRoomPartnerBannerDefault']){
                $meetingRoomPartnerAssetUrl = MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->value('banner');

                if($meetingRoomPartnerAssetUrl){
                    $this->removeMeetingRoomPartnerAssetInStorage($meetingRoomPartnerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/meeting-room-partners/banner', $fileName);

            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'banner' => $path,
            ]);

            $this->meetingRoomPartnerData['meetingRoomPartnerBanner'] = Storage::url($path);
            $this->meetingRoomPartnerData['meetingRoomPartnerBannerDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditMeetingRoomPartnerAssetFields();
    }

    public function removeMeetingRoomPartnerAssetConfirmation(){
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeMeetingRoomPartnerAssetConfirmed",
        ]);
    }

    public function removeMeetingRoomPartnerAsset(){
        if($this->assetType == "Meeting room partner logo"){
            $meetingRoomPartnerAssetUrl = MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->value('logo');

            if($meetingRoomPartnerAssetUrl){
                $this->removeMeetingRoomPartnerAssetInStorage($meetingRoomPartnerAssetUrl);
            }

            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'logo' => null,
            ]);

            $this->meetingRoomPartnerData['meetingRoomPartnerLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->meetingRoomPartnerData['meetingRoomPartnerLogoDefault'] = true;
        } else {
            $meetingRoomPartnerAssetUrl = MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->value('banner');
            
            if($meetingRoomPartnerAssetUrl){
                $this->removeMeetingRoomPartnerAssetInStorage($meetingRoomPartnerAssetUrl);
            }

            MeetingRoomPartners::where('id', $this->meetingRoomPartnerData['meetingRoomPartnerId'])->update([
                'banner' => null,
            ]);

            $this->meetingRoomPartnerData['meetingRoomPartnerBanner'] = asset('assets/images/banner-placeholder.jpg');
            $this->meetingRoomPartnerData['meetingRoomPartnerBannerDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditMeetingRoomPartnerAssetFields();
    }

    public function removeMeetingRoomPartnerAssetInStorage($storageUrl){
        if(Storage::exists($storageUrl)){
            Storage::delete($storageUrl);
        }
    }




    // EDIT MEETING ROOM PARTNER DETAILS
    public function showEditMeetingRoomPartnerDetails()
    {
        $this->name = $this->meetingRoomPartnerData['meetingRoomPartnerName'];
        $this->profile = $this->meetingRoomPartnerData['meetingRoomPartnerProfile'];
        $this->location = $this->meetingRoomPartnerData['meetingRoomPartnerLocation'];
        
        
        $this->country = $this->meetingRoomPartnerData['meetingRoomPartnerCountry'];
        $this->contact_person_name = $this->meetingRoomPartnerData['meetingRoomPartnerContactPersonName'];
        $this->email_address = $this->meetingRoomPartnerData['meetingRoomPartnerEmailAddress'];
        $this->mobile_number = $this->meetingRoomPartnerData['meetingRoomPartnerMobileNumber'];
        $this->website = $this->meetingRoomPartnerData['meetingRoomPartnerWebsite'];
        $this->facebook = $this->meetingRoomPartnerData['meetingRoomPartnerFacebook'];
        $this->linkedin = $this->meetingRoomPartnerData['meetingRoomPartnerLinkedin'];
        $this->twitter = $this->meetingRoomPartnerData['meetingRoomPartnerTwitter'];
        $this->instagram = $this->meetingRoomPartnerData['meetingRoomPartnerInstagram'];
        $this->editMeetingRoomPartnerDetailsForm = true;
    }

    public function cancelEditMeetingRoomPartnerDetails()
    {
        $this->resetEditMeetingRoomPartnerDetailsFields();
    }

    public function resetEditMeetingRoomPartnerDetailsFields()
    {
        $this->editMeetingRoomPartnerDetailsForm = false;
        $this->name = null;
        $this->profile = null;
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
            'website' => 'required',
            'location' => 'required',
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
            'profile' => $this->profile,
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

        $this->meetingRoomPartnerData['meetingRoomPartnerName'] = $this->name;
        $this->meetingRoomPartnerData['meetingRoomPartnerProfile'] = $this->profile;
        $this->meetingRoomPartnerData['meetingRoomPartnerLocation'] = $this->location;
        
        $this->meetingRoomPartnerData['meetingRoomPartnerCountry'] = $this->country;
        $this->meetingRoomPartnerData['meetingRoomPartnerContactPersonName'] = $this->contact_person_name;
        $this->meetingRoomPartnerData['meetingRoomPartnerEmailAddress'] = $this->email_address;
        $this->meetingRoomPartnerData['meetingRoomPartnerMobileNumber'] = $this->mobile_number;
        $this->meetingRoomPartnerData['meetingRoomPartnerWebsite'] = $this->website;
        $this->meetingRoomPartnerData['meetingRoomPartnerFacebook'] = $this->facebook;
        $this->meetingRoomPartnerData['meetingRoomPartnerLinkedin'] = $this->linkedin;
        $this->meetingRoomPartnerData['meetingRoomPartnerTwitter'] = $this->twitter;
        $this->meetingRoomPartnerData['meetingRoomPartnerInstagram'] = $this->instagram;

        $this->resetEditMeetingRoomPartnerDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Meeting room partner details updated succesfully!',
            'text' => "",
        ]);
    }
}
