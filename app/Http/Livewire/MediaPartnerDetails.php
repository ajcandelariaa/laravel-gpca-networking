<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\MediaPartner as MediaPartners;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class MediaPartnerDetails extends Component
{
    use WithFileUploads;

    public $event, $mediaPartnerData;

    public $image, $assetType, $editMediaPartnerAssetForm, $imageDefault;

    public $name, $profile, $country, $contact_person_name, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram, $editMediaPartnerDetailsForm;

    protected $listeners = ['editMediaPartnerDetailsConfirmed' => 'editMediaPartnerDetails', 'editMediaPartnerAssetConfirmed' => 'editMediaPartnerAsset', 'removeMediaPartnerAssetConfirmed' => 'removeMediaPartnerAsset'];

    public function mount($eventId, $eventCategory, $mediaPartnerData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->mediaPartnerData = $mediaPartnerData;

        $this->editMediaPartnerAssetForm = false;
        $this->editMediaPartnerDetailsForm = false;

    }

    public function render()
    {
        return view('livewire.event.media-partners.media-partner-details');
    }









    // EDIT MEDIA PARTNER ASSET
    public function showEditMediaPartnerAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editMediaPartnerAssetForm = true;
    }

    public function cancelEditMediaPartnerAsset()
    {
        $this->resetEditMediaPartnerAssetFields();
    }

    public function resetEditMediaPartnerAssetFields()
    {
        $this->editMediaPartnerAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editMediaPartnerAssetConfirmation()
    {
        
        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editMediaPartnerAssetConfirmed",
        ]);
    }

    public function editMediaPartnerAsset()
    {
        $fileName = Str::of($this->image->getClientOriginalName())->replace([' ', '-'], '_')->lower();

        if ($this->assetType == "Media partner logo") {
            $tempPath = 'public/' . $this->event->year  . '/' . $this->event->category . '/media-partners/logo/' . $this->mediaPartnerData['mediaPartnerId'];

            if(!$this->mediaPartnerData['mediaPartnerLogoDefault']){
                $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('logo');
                if($mediaPartnerAssetUrl){
                    $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl, $tempPath);
                }
            }

            $path = $this->image->storeAs($tempPath, $fileName);
            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'logo' => $path,
            ]);

            $this->mediaPartnerData['mediaPartnerLogo'] = Storage::url($path);
            $this->mediaPartnerData['mediaPartnerLogoDefault'] = false;
        } else {
            $tempPath = 'public/' . $this->event->year  . '/' . $this->event->category . '/media-partners/banner/' . $this->mediaPartnerData['mediaPartnerId'];

            if(!$this->mediaPartnerData['mediaPartnerBannerDefault']){
                $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('banner');

                if($mediaPartnerAssetUrl){
                    $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl, $tempPath);
                }
            }

            $path = $this->image->storeAs($tempPath, $fileName);
            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'banner' => $path,
            ]);

            $this->mediaPartnerData['mediaPartnerBanner'] = Storage::url($path);
            $this->mediaPartnerData['mediaPartnerBannerDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditMediaPartnerAssetFields();
    }

    public function removeMediaPartnerAssetConfirmation(){
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeMediaPartnerAssetConfirmed",
        ]);
    }

    public function removeMediaPartnerAsset(){
        if($this->assetType == "Media partner logo"){
            $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('logo');

            $pathDirectory = 'public/' . $this->event->year  . '/' . $this->event->category . '/media-partners/logo/' . $this->mediaPartnerData['mediaPartnerId'];

            if($mediaPartnerAssetUrl){
                $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl, $pathDirectory);
            }

            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'logo' => null,
            ]);

            $this->mediaPartnerData['mediaPartnerLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->mediaPartnerData['mediaPartnerLogoDefault'] = true;
        } else {
            $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('banner');

            $pathDirectory = 'public/' . $this->event->year  . '/' . $this->event->category . '/media-partners/banner/' . $this->mediaPartnerData['mediaPartnerId'];

            if($mediaPartnerAssetUrl){
                $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl, $pathDirectory);
            }

            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'banner' => null,
            ]);

            $this->mediaPartnerData['mediaPartnerBanner'] = asset('assets/images/banner-placeholder.jpg');
            $this->mediaPartnerData['mediaPartnerBannerDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditMediaPartnerAssetFields();
    }

    public function removeMediaPartnerAssetInStorage($storageUrl, $storageDirectory){
        if(Storage::exists($storageUrl)){
            Storage::delete($storageUrl);
            Storage::deleteDirectory($storageDirectory);
        }
    }




    // EDIT MEDIA PARTNER DETAILS
    public function showEditMediaPartnerDetails()
    {
        $this->name = $this->mediaPartnerData['mediaPartnerName'];
        $this->profile = $this->mediaPartnerData['mediaPartnerProfile'];
        
        $this->country = $this->mediaPartnerData['mediaPartnerCountry'];
        $this->contact_person_name = $this->mediaPartnerData['mediaPartnerContactPersonName'];
        $this->email_address = $this->mediaPartnerData['mediaPartnerEmailAddress'];
        $this->mobile_number = $this->mediaPartnerData['mediaPartnerMobileNumber'];
        $this->website = $this->mediaPartnerData['mediaPartnerWebsite'];
        $this->facebook = $this->mediaPartnerData['mediaPartnerFacebook'];
        $this->linkedin = $this->mediaPartnerData['mediaPartnerLinkedin'];
        $this->twitter = $this->mediaPartnerData['mediaPartnerTwitter'];
        $this->instagram = $this->mediaPartnerData['mediaPartnerInstagram'];
        $this->editMediaPartnerDetailsForm = true;
    }

    public function cancelEditMediaPartnerDetails()
    {
        $this->resetEditMediaPartnerDetailsFields();
    }

    public function resetEditMediaPartnerDetailsFields()
    {
        $this->editMediaPartnerDetailsForm = false;
        $this->name = null;
        $this->profile = null;

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

    public function editMediaPartnerDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'website' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editMediaPartnerDetailsConfirmed",
        ]);
    }

    public function editMediaPartnerDetails()
    {
        MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
            'name' => $this->name,
            'profile' => $this->profile,

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

        $this->mediaPartnerData['mediaPartnerName'] = $this->name;
        $this->mediaPartnerData['mediaPartnerProfile'] = $this->profile;
        
        $this->mediaPartnerData['mediaPartnerCountry'] = $this->country;
        $this->mediaPartnerData['mediaPartnerContactPersonName'] = $this->contact_person_name;
        $this->mediaPartnerData['mediaPartnerEmailAddress'] = $this->email_address;
        $this->mediaPartnerData['mediaPartnerMobileNumber'] = $this->mobile_number;
        $this->mediaPartnerData['mediaPartnerWebsite'] = $this->website;
        $this->mediaPartnerData['mediaPartnerFacebook'] = $this->facebook;
        $this->mediaPartnerData['mediaPartnerLinkedin'] = $this->linkedin;
        $this->mediaPartnerData['mediaPartnerTwitter'] = $this->twitter;
        $this->mediaPartnerData['mediaPartnerInstagram'] = $this->instagram;

        $this->resetEditMediaPartnerDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media partner details updated succesfully!',
            'text' => "",
        ]);
    }
}
