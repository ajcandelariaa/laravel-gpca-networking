<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\MediaPartner as MediaPartners;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class MediaPartnerDetails extends Component
{
    use WithFileUploads;

    public $event, $mediaPartnerData;

    public $image, $assetType, $editMediaPartnerAssetForm, $imageDefault;

    public $name, $profile, $email_address, $mobile_number, $link, $editMediaPartnerDetailsForm;

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
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Media partner logo") {

            if(!$this->mediaPartnerData['mediaPartnerLogoDefault']){
                $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('logo');
                if($mediaPartnerAssetUrl){
                    $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/media-partners/logo', $fileName);

            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'logo' => $path,
            ]);

            $this->mediaPartnerData['mediaPartnerLogo'] = Storage::url($path);
            $this->mediaPartnerData['mediaPartnerLogoDefault'] = false;
        } else {
            
            if(!$this->mediaPartnerData['mediaPartnerBannerDefault']){
                $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('banner');

                if($mediaPartnerAssetUrl){
                    $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/media-partners/banner', $fileName);

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

            if($mediaPartnerAssetUrl){
                $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl);
            }

            MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->update([
                'logo' => null,
            ]);

            $this->mediaPartnerData['mediaPartnerLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->mediaPartnerData['mediaPartnerLogoDefault'] = true;
        } else {
            $mediaPartnerAssetUrl = MediaPartners::where('id', $this->mediaPartnerData['mediaPartnerId'])->value('banner');
            
            if($mediaPartnerAssetUrl){
                $this->removeMediaPartnerAssetInStorage($mediaPartnerAssetUrl);
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

    public function removeMediaPartnerAssetInStorage($storageUrl){
        if(Storage::exists($storageUrl)){
            Storage::delete($storageUrl);
        }
    }




    // EDIT MEDIA PARTNER DETAILS
    public function showEditMediaPartnerDetails()
    {
        $this->name = $this->mediaPartnerData['mediaPartnerName'];
        $this->profile = $this->mediaPartnerData['mediaPartnerProfile'];
        $this->email_address = $this->mediaPartnerData['mediaPartnerEmailAddress'];
        $this->mobile_number = $this->mediaPartnerData['mediaPartnerMobileNumber'];
        $this->link = $this->mediaPartnerData['mediaPartnerLink'];
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
        $this->email_address = null;
        $this->mobile_number = null;
        $this->link = null;
    }

    public function editMediaPartnerDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'link' => 'required',
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
            'email_address' => $this->email_address,
            'mobile_number' => $this->mobile_number,
            'link' => $this->link,
        ]);

        $this->mediaPartnerData['mediaPartnerName'] = $this->name;
        $this->mediaPartnerData['mediaPartnerProfile'] = $this->profile;
        $this->mediaPartnerData['mediaPartnerEmailAddress'] = $this->email_address;
        $this->mediaPartnerData['mediaPartnerMobileNumber'] = $this->mobile_number;
        $this->mediaPartnerData['mediaPartnerLink'] = $this->link;

        $this->resetEditMediaPartnerDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Media partner details updated succesfully!',
            'text' => "",
        ]);
    }
}
