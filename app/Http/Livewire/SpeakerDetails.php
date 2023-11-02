<?php

namespace App\Http\Livewire;

use App\Models\Event as Events;
use App\Models\Speaker as Speakers;
use App\Models\SpeakerType as SpeakerTypes;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class SpeakerDetails extends Component
{
    use WithFileUploads;

    public $event, $salutations, $speakerData;

    public $image, $assetType, $editSpeakerAssetForm, $imageDefault;

    public $category, $type, $salutation, $first_name, $middle_name, $last_name, $company_name, $job_title, $biography, $country, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram, $editSpeakerDetailsForm, $categoryChoices = array(), $typeChoices = array();

    protected $listeners = ['editSpeakerDetailsConfirmed' => 'editSpeakerDetails', 'editSpeakerAssetConfirmed' => 'editSpeakerAsset', 'removeSpeakerAssetConfirmed' => 'removeSpeakerAsset'];

    public function mount($eventId, $eventCategory, $speakerData)
    {
        $this->salutations = config('app.salutations');
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->speakerData = $speakerData;

        $this->editSpeakerAssetForm = false;
        $this->editSpeakerDetailsForm = false;
    }
    public function render()
    {
        return view('livewire.event.speakers.speaker-details');
    }


    // EDIT SPEAKER DETAILS
    public function showEditSpeakerDetails()
    {
        $speakerTypes = SpeakerTypes::where('event_id', $this->event->id)->get();

        if ($speakerTypes->isNotEmpty()) {
            foreach ($speakerTypes as $speakerType) {
                array_push($this->typeChoices, [
                    'value' => $speakerType->name,
                    'id' => $speakerType->id,
                ]);
            }
        }

        $features = Features::where('event_id', $this->event->id)->get();
        if ($features->isNotEmpty()) {

            array_push($this->categoryChoices, [
                'value' => $this->event->short_name,
                'id' => 0,
            ]);

            foreach ($features as $feature) {
                array_push($this->categoryChoices, [
                    'value' => $feature->short_name,
                    'id' => $feature->id,
                ]);
            }
        }
        $this->category = $this->speakerData['speakerFeatureId'];
        $this->type = $this->speakerData['speakerTypeId'];

        $this->salutation = $this->speakerData['speakerSalutation'];
        $this->first_name = $this->speakerData['speakerFirstName'];
        $this->middle_name = $this->speakerData['speakerMiddleName'];
        $this->last_name = $this->speakerData['speakerLastName'];
        $this->company_name = $this->speakerData['speakerCompanyName'];
        $this->job_title = $this->speakerData['speakerJobTitle'];
        $this->biography = $this->speakerData['speakerBiography'];

        $this->country = $this->speakerData['speakerCountry'];
        $this->email_address = $this->speakerData['speakerEmailAddress'];
        $this->mobile_number = $this->speakerData['speakerMobileNumber'];
        $this->website = $this->speakerData['speakerWebsite'];
        $this->facebook = $this->speakerData['speakerFacebook'];
        $this->linkedin = $this->speakerData['speakerLinkedin'];
        $this->twitter = $this->speakerData['speakerTwitter'];
        $this->instagram = $this->speakerData['speakerInstagram'];
        $this->editSpeakerDetailsForm = true;
    }

    public function cancelEditSpeakerDetails()
    {
        $this->resetEditSpeakerDetailsFields();
    }

    public function resetEditSpeakerDetailsFields()
    {
        $this->editSpeakerDetailsForm = false;
        $this->category = null;
        $this->type = null;
        $this->salutation = null;
        $this->first_name = null;
        $this->middle_name = null;
        $this->last_name = null;
        $this->company_name = null;
        $this->job_title = null;
        $this->biography = null;

        $this->country = null;
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

    public function editSpeakerDetailsConfirmation()
    {
        $this->validate([
            'category' => 'required',
            'type' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'company_name' => 'required',
            'job_title' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editSpeakerDetailsConfirmed",
        ]);
    }

    public function editSpeakerDetails()
    {
        Speakers::where('id', $this->speakerData['speakerId'])->update([
            'feature_id' => $this->category,
            'speaker_type_id' => $this->type,
            'salutation' => $this->salutation,
            'first_name' => $this->first_name,
            'middle_name' => $this->middle_name,
            'last_name' => $this->last_name,
            'company_name' => $this->company_name,
            'job_title' => $this->job_title,
            'biography' => $this->biography,

            'country' => $this->country == "" ? null : $this->country,
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
        
        $this->speakerData['speakerCategoryName'] = $selectedCategory;
        $this->speakerData['speakerFeatureId'] = $this->category;
        $this->speakerData['speakerTypeName'] = $selectedType;
        $this->speakerData['speakerTypeId'] = $this->type;
        $this->speakerData['speakerSalutation'] = $this->salutation;
        $this->speakerData['speakerFirstName'] = $this->first_name;
        $this->speakerData['speakerMiddleName'] = $this->middle_name;
        $this->speakerData['speakerLastName'] = $this->last_name;
        $this->speakerData['speakerCompanyName'] = $this->company_name;
        $this->speakerData['speakerJobTitle'] = $this->job_title;
        $this->speakerData['speakerBiography'] = $this->biography;

        $this->speakerData['speakerCountry'] = $this->country;
        $this->speakerData['speakerEmailAddress'] = $this->email_address;
        $this->speakerData['speakerMobileNumber'] = $this->mobile_number;
        $this->speakerData['speakerWebsite'] = $this->website;
        $this->speakerData['speakerFacebook'] = $this->facebook;
        $this->speakerData['speakerLinkedin'] = $this->linkedin;
        $this->speakerData['speakerTwitter'] = $this->twitter;
        $this->speakerData['speakerInstagram'] = $this->instagram;

        $this->resetEditSpeakerDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Speaker details updated succesfully!',
            'text' => "",
        ]);
    }





    // EDIT SPEAKER ASSET
    public function showEditSpeakerAsset($assetType)
    {
        // dd($this->speakerData);
        $this->assetType = $assetType;
        $this->editSpeakerAssetForm = true;
    }

    public function cancelEditSpeakerAsset()
    {
        $this->resetEditSpeakerAssetFields();
    }

    public function resetEditSpeakerAssetFields()
    {
        $this->editSpeakerAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editSpeakerAssetConfirmation()
    {

        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editSpeakerAssetConfirmed",
        ]);
    }

    public function editSpeakerAsset()
    {
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Speaker PFP") {

            if (!$this->speakerData['speakerPFPDefault']) {
                $speakerAssetUrl = Speakers::where('id', $this->speakerData['speakerId'])->value('pfp');

                if ($speakerAssetUrl) {
                    $this->removeSpeakerAssetInStorage($speakerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/speakers/pfp', $fileName);

            Speakers::where('id', $this->speakerData['speakerId'])->update([
                'pfp' => $path,
            ]);

            $this->speakerData['speakerPFP'] = Storage::url($path);
            $this->speakerData['speakerPFPDefault'] = false;
        } else {

            if (!$this->speakerData['speakerCoverPhotoDefault']) {
                $speakerAssetUrl = Speakers::where('id', $this->speakerData['speakerId'])->value('cover_photo');

                if ($speakerAssetUrl) {
                    $this->removeSpeakerAssetInStorage($speakerAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/speakers/cover-photo', $fileName);

            Speakers::where('id', $this->speakerData['speakerId'])->update([
                'cover_photo' => $path,
            ]);

            $this->speakerData['speakerCoverPhoto'] = Storage::url($path);
            $this->speakerData['speakerCoverPhotoDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditSpeakerAssetFields();
    }

    public function removeSpeakerAssetConfirmation()
    {
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeSpeakerAssetConfirmed",
        ]);
    }

    public function removeSpeakerAsset()
    {
        if ($this->assetType == "Speaker PFP") {
            $speakerAssetUrl = Speakers::where('id', $this->speakerData['speakerId'])->value('pfp');

            if ($speakerAssetUrl) {
                $this->removeSpeakerAssetInStorage($speakerAssetUrl);
            }

            Speakers::where('id', $this->speakerData['speakerId'])->update([
                'pfp' => null,
            ]);

            $this->speakerData['speakerPFP'] = asset('assets/images/pfp-placeholder.jpg');
            $this->speakerData['speakerPFPDefault'] = true;
        } else {
            $speakerAssetUrl = Speakers::where('id', $this->speakerData['speakerId'])->value('cover_photo');

            if ($speakerAssetUrl) {
                $this->removeSpeakerAssetInStorage($speakerAssetUrl);
            }

            Speakers::where('id', $this->speakerData['speakerId'])->update([
                'cover_photo' => null,
            ]);

            $this->speakerData['speakerCoverPhoto'] = asset('assets/images/cover-photo-placeholder.jpg');
            $this->speakerData['speakerCoverPhotoDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditSpeakerAssetFields();
    }

    public function removeSpeakerAssetInStorage($storageUrl)
    {
        if (Storage::exists($storageUrl)) {
            Storage::delete($storageUrl);
        }
    }


    public function deleteSpeakerConfirmation()
    {
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeSpeakerAssetConfirmed",
        ]);
    }
}
