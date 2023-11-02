<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Sponsor as Sponsors;
use App\Models\SponsorType as SponsorTypes;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class SponsorDetails extends Component
{
    use WithFileUploads;

    public $event, $sponsorData;

    public $image, $assetType, $editSponsorAssetForm, $imageDefault;

    public $category, $type, $name, $profile, $country, $contact_person_name, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram, $editSponsorDetailsForm, $categoryChoices = array(), $typeChoices = array();

    protected $listeners = ['editSponsorDetailsConfirmed' => 'editSponsorDetails', 'editSponsorAssetConfirmed' => 'editSponsorAsset', 'removeSponsorAssetConfirmed' => 'removeSponsorAsset'];

    public function mount($eventId, $eventCategory, $sponsorData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->sponsorData = $sponsorData;

        $this->editSponsorAssetForm = false;
        $this->editSponsorDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.sponsors.sponsor-details');
    }


    // EDIT SPONSOR DETAILS
    public function showEditSponsorDetails()
    {
        $sponsorTypes = SponsorTypes::where('event_id', $this->event->id)->get();

        if($sponsorTypes->isNotEmpty()){
            foreach($sponsorTypes as $sponsorType){
                array_push($this->typeChoices, [
                    'value' => $sponsorType->name,
                    'id' => $sponsorType->id,
                ]);
            }
        }

        $features = Features::where('event_id', $this->event->id)->get();
        if($features->isNotEmpty()){

            array_push($this->categoryChoices, [
                'value' => $this->event->short_name,
                'id' => 0,
            ]);

            foreach($features as $feature){
                array_push($this->categoryChoices, [
                    'value' => $feature->short_name,
                    'id' => $feature->id,
                ]);
            }
        }
        $this->category = $this->sponsorData['sponsorFeatureId'];
        $this->type = $this->sponsorData['sponsorTypeId'];

        $this->name = $this->sponsorData['sponsorName'];
        $this->profile = $this->sponsorData['sponsorProfile'];
        
        $this->country = $this->sponsorData['sponsorCountry'];
        $this->contact_person_name = $this->sponsorData['sponsorContactPersonName'];
        $this->email_address = $this->sponsorData['sponsorEmailAddress'];
        $this->mobile_number = $this->sponsorData['sponsorMobileNumber'];
        $this->website = $this->sponsorData['sponsorWebsite'];
        $this->facebook = $this->sponsorData['sponsorFacebook'];
        $this->linkedin = $this->sponsorData['sponsorLinkedin'];
        $this->twitter = $this->sponsorData['sponsorTwitter'];
        $this->instagram = $this->sponsorData['sponsorInstagram'];
        $this->editSponsorDetailsForm = true;
    }

    public function cancelEditSponsorDetails()
    {
        $this->resetEditSponsorDetailsFields();
    }

    public function resetEditSponsorDetailsFields()
    {
        $this->editSponsorDetailsForm = false;
        $this->category = null;
        $this->type = null;
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

        $this->typeChoices = array();
        $this->categoryChoices = array();
    }

    public function editSponsorDetailsConfirmation()
    {
        $this->validate([
            'category' => 'required',
            'type' => 'required',
            'name' => 'required',
            'website' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editSponsorDetailsConfirmed",
        ]);
    }

    public function editSponsorDetails()
    {
        Sponsors::where('id', $this->sponsorData['sponsorId'])->update([
            'feature_id' => $this->category,
            'sponsor_type_id' => $this->type,
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

        
        foreach($this->categoryChoices as $categoryChoice){
            if($categoryChoice['id'] == $this->category){
                $selectedCategory = $categoryChoice['value'];
            }
        }

        foreach($this->typeChoices as $typeChoice){
            if($typeChoice['id'] == $this->type){
                $selectedType = $typeChoice['value'];
            }
        }

        $this->sponsorData['sponsorCategoryName'] = $selectedCategory;
        $this->sponsorData['sponsorFeatureId'] = $this->category;
        $this->sponsorData['sponsorTypeName'] = $selectedType;
        $this->sponsorData['sponsorTypeId'] = $this->type;

        $this->sponsorData['sponsorName'] = $this->name;
        $this->sponsorData['sponsorProfile'] = $this->profile;

        $this->sponsorData['sponsorCountry'] = $this->country;
        $this->sponsorData['sponsorContactPersonName'] = $this->contact_person_name;
        $this->sponsorData['sponsorEmailAddress'] = $this->email_address;
        $this->sponsorData['sponsorMobileNumber'] = $this->mobile_number;
        $this->sponsorData['sponsorWebsite'] = $this->website;
        $this->sponsorData['sponsorFacebook'] = $this->facebook;
        $this->sponsorData['sponsorLinkedin'] = $this->linkedin;
        $this->sponsorData['sponsorTwitter'] = $this->twitter;
        $this->sponsorData['sponsorInstagram'] = $this->instagram;

        $this->resetEditSponsorDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Sponsor details updated succesfully!',
            'text' => "",
        ]);
    }




    // EDIT SPONSOR ASSET
    public function showEditSponsorAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editSponsorAssetForm = true;
    }

    public function cancelEditSponsorAsset()
    {
        $this->resetEditSponsorAssetFields();
    }

    public function resetEditSponsorAssetFields()
    {
        $this->editSponsorAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editSponsorAssetConfirmation()
    {
        
        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editSponsorAssetConfirmed",
        ]);
    }

    public function editSponsorAsset()
    {
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Sponsor logo") {

            if(!$this->sponsorData['sponsorLogoDefault']){
                $sponsorAssetUrl = Sponsors::where('id', $this->sponsorData['sponsorId'])->value('logo');
                if($sponsorAssetUrl){
                    $this->removeSponsorAssetInStorage($sponsorAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/sponsors/logo', $fileName);

            Sponsors::where('id', $this->sponsorData['sponsorId'])->update([
                'logo' => $path,
            ]);

            $this->sponsorData['sponsorLogo'] = Storage::url($path);
            $this->sponsorData['sponsorLogoDefault'] = false;
        } else {
            
            if(!$this->sponsorData['sponsorBannerDefault']){
                $sponsorAssetUrl = Sponsors::where('id', $this->sponsorData['sponsorId'])->value('banner');

                if($sponsorAssetUrl){
                    $this->removeSponsorAssetInStorage($sponsorAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/sponsors/banner', $fileName);

            Sponsors::where('id', $this->sponsorData['sponsorId'])->update([
                'banner' => $path,
            ]);

            $this->sponsorData['sponsorBanner'] = Storage::url($path);
            $this->sponsorData['sponsorBannerDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditSponsorAssetFields();
    }

    public function removeSponsorAssetConfirmation(){
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeSponsorAssetConfirmed",
        ]);
    }

    public function removeSponsorAsset(){
        if($this->assetType == "Sponsor logo"){
            $sponsorAssetUrl = Sponsors::where('id', $this->sponsorData['sponsorId'])->value('logo');

            if($sponsorAssetUrl){
                $this->removeSponsorAssetInStorage($sponsorAssetUrl);
            }

            Sponsors::where('id', $this->sponsorData['sponsorId'])->update([
                'logo' => null,
            ]);

            $this->sponsorData['sponsorLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->sponsorData['sponsorLogoDefault'] = true;
        } else {
            $sponsorAssetUrl = Sponsors::where('id', $this->sponsorData['sponsorId'])->value('banner');
            
            if($sponsorAssetUrl){
                $this->removeSponsorAssetInStorage($sponsorAssetUrl);
            }

            Sponsors::where('id', $this->sponsorData['sponsorId'])->update([
                'banner' => null,
            ]);

            $this->sponsorData['sponsorBanner'] = asset('assets/images/banner-placeholder.jpg');
            $this->sponsorData['sponsorBannerDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditSponsorAssetFields();
    }

    public function removeSponsorAssetInStorage($storageUrl){
        if(Storage::exists($storageUrl)){
            Storage::delete($storageUrl);
        }
    }



}
