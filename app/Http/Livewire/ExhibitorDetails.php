<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Exhibitor as Exhibitors;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;
use Illuminate\Support\Str;

class ExhibitorDetails extends Component
{
    use WithFileUploads;

    public $event, $exhibitorData;

    public $image, $assetType, $editExhibitorAssetForm, $imageDefault;

    public $name, $profile, $stand_number, $country, $contact_person_name, $email_address, $mobile_number, $website, $facebook, $linkedin, $twitter, $instagram,  $editExhibitorDetailsForm;

    protected $listeners = ['editExhibitorDetailsConfirmed' => 'editExhibitorDetails', 'editExhibitorAssetConfirmed' => 'editExhibitorAsset', 'removeExhibitorAssetConfirmed' => 'removeExhibitorAsset'];

    public function mount($eventId, $eventCategory, $exhibitorData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->exhibitorData = $exhibitorData;

        $this->editExhibitorAssetForm = false;
        $this->editExhibitorDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.exhibitors.exhibitor-details');
    }



    // EDIT EXHIBITOR ASSET
    public function showEditExhibitorAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editExhibitorAssetForm = true;
    }

    public function cancelEditExhibitorAsset()
    {
        $this->resetEditExhibitorAssetFields();
    }

    public function resetEditExhibitorAssetFields()
    {
        $this->editExhibitorAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editExhibitorAssetConfirmation()
    {
        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editExhibitorAssetConfirmed",
        ]);
    }

    public function editExhibitorAsset()
    {
        $fileName = Str::of($this->image->getClientOriginalName())->replace([' ', '-'], '_')->lower();

        if ($this->assetType == "Exhibitor logo") {
            $tempPath = 'public/' . $this->event->year  . '/' . $this->event->category . '/exhibitors/logo/' . $this->exhibitorData['exhibitorId'];
            if (!$this->exhibitorData['exhibitorLogoDefault']) {
                $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('logo');
                if ($exhibitorAssetUrl) {
                    $this->removeExhibitorAssetInStorage($exhibitorAssetUrl, $tempPath);
                }
            }

            $path = $this->image->storeAs($tempPath, $fileName);
            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'logo' => $path,
            ]);

            $this->exhibitorData['exhibitorLogo'] = Storage::url($path);
            $this->exhibitorData['exhibitorLogoDefault'] = false;
        } else {
            $tempPath = 'public/' . $this->event->year  . '/' . $this->event->category . '/exhibitors/banner/' . $this->exhibitorData['exhibitorId'];
            if (!$this->exhibitorData['exhibitorBannerDefault']) {
                $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('banner');
                if ($exhibitorAssetUrl) {
                    $this->removeExhibitorAssetInStorage($exhibitorAssetUrl, $tempPath);
                }
            }

            $path = $this->image->storeAs($tempPath, $fileName);
            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'banner' => $path,
            ]);

            $this->exhibitorData['exhibitorBanner'] = Storage::url($path);
            $this->exhibitorData['exhibitorBannerDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditExhibitorAssetFields();
    }

    public function removeExhibitorAssetConfirmation()
    {
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeExhibitorAssetConfirmed",
        ]);
    }

    public function removeExhibitorAsset()
    {
        if ($this->assetType == "Exhibitor logo") {
            $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('logo');
            $pathDirectory = 'public/' . $this->event->year  . '/' . $this->event->category . '/exhibitors/logo/' . $this->exhibitorData['exhibitorId'];

            if ($exhibitorAssetUrl) {
                $this->removeExhibitorAssetInStorage($exhibitorAssetUrl, $pathDirectory);
            }

            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'logo' => null,
            ]);

            $this->exhibitorData['exhibitorLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->exhibitorData['exhibitorLogoDefault'] = true;
        } else {
            $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('banner');
            $pathDirectory = 'public/' . $this->event->year  . '/' . $this->event->category . '/exhibitors/banner/' . $this->exhibitorData['exhibitorId'];

            if ($exhibitorAssetUrl) {
                $this->removeExhibitorAssetInStorage($exhibitorAssetUrl, $pathDirectory);
            }

            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'banner' => null,
            ]);

            $this->exhibitorData['exhibitorBanner'] = asset('assets/images/banner-placeholder.jpg');
            $this->exhibitorData['exhibitorBannerDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditExhibitorAssetFields();
    }

    public function removeExhibitorAssetInStorage($storageUrl, $storageDirectory)
    {
        if (Storage::exists($storageUrl)) {
            Storage::delete($storageUrl);
            Storage::deleteDirectory($storageDirectory);
        }
    }



    // EDIT EXHIBITOR DETAILS
    public function showEditExhibitorDetails()
    {
        $this->name = $this->exhibitorData['exhibitorName'];
        $this->profile = $this->exhibitorData['exhibitorProfile'];
        $this->stand_number = $this->exhibitorData['exhibitorStandNumber'];

        $this->country = $this->exhibitorData['exhibitorCountry'];
        $this->contact_person_name = $this->exhibitorData['exhibitorContactPersonName'];
        $this->email_address = $this->exhibitorData['exhibitorEmailAddress'];
        $this->mobile_number = $this->exhibitorData['exhibitorMobileNumber'];
        $this->website = $this->exhibitorData['exhibitorWebsite'];
        $this->facebook = $this->exhibitorData['exhibitorFacebook'];
        $this->linkedin = $this->exhibitorData['exhibitorLinkedin'];
        $this->twitter = $this->exhibitorData['exhibitorTwitter'];
        $this->instagram = $this->exhibitorData['exhibitorInstagram'];

        $this->editExhibitorDetailsForm = true;
    }

    public function cancelEditExhibitorDetails()
    {
        $this->resetEditExhibitorDetailsFields();
    }

    public function resetEditExhibitorDetailsFields()
    {
        $this->editExhibitorDetailsForm = false;
        $this->name = null;
        $this->profile = null;
        $this->stand_number = null;

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

    public function editExhibitorDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'website' => 'required',
            'stand_number' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editExhibitorDetailsConfirmed",
        ]);
    }

    public function editExhibitorDetails()
    {
        Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
            'name' => $this->name,
            'profile' => $this->profile,
            'stand_number' => $this->stand_number,

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

        $this->exhibitorData['exhibitorName'] = $this->name;
        $this->exhibitorData['exhibitorProfile'] = $this->profile;
        $this->exhibitorData['exhibitorStandNumber'] = $this->stand_number;

        $this->exhibitorData['exhibitorCountry'] = $this->country;
        $this->exhibitorData['exhibitorContactPersonName'] = $this->contact_person_name;
        $this->exhibitorData['exhibitorEmailAddress'] = $this->email_address;
        $this->exhibitorData['exhibitorMobileNumber'] = $this->mobile_number;
        $this->exhibitorData['exhibitorWebsite'] = $this->website;
        $this->exhibitorData['exhibitorFacebook'] = $this->facebook;
        $this->exhibitorData['exhibitorLinkedin'] = $this->linkedin;
        $this->exhibitorData['exhibitorTwitter'] = $this->twitter;
        $this->exhibitorData['exhibitorInstagram'] = $this->instagram;

        $this->resetEditExhibitorDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Exhibitor details updated succesfully!',
            'text' => "",
        ]);
    }
}
