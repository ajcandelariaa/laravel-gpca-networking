<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Exhibitor as Exhibitors;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class ExhibitorDetails extends Component
{
    use WithFileUploads;

    public $event, $exhibitorData;

    public $image, $assetType, $editExhibitorAssetForm, $imageDefault;

    public $name, $profile, $stand_number, $email_address, $mobile_number, $link, $editExhibitorDetailsForm;

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
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Exhibitor logo") {

            if(!$this->exhibitorData['exhibitorLogoDefault']){
                $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('logo');
                if($exhibitorAssetUrl){
                    $this->removeExhibitorAssetInStorage($exhibitorAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/exhibitors/logo', $fileName);

            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'logo' => $path,
            ]);

            $this->exhibitorData['exhibitorLogo'] = Storage::url($path);
            $this->exhibitorData['exhibitorLogoDefault'] = false;
        } else {
            
            if(!$this->exhibitorData['exhibitorBannerDefault']){
                $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('banner');

                if($exhibitorAssetUrl){
                    $this->removeExhibitorAssetInStorage($exhibitorAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/exhibitors/banner', $fileName);

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

    public function removeExhibitorAssetConfirmation(){
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeExhibitorAssetConfirmed",
        ]);
    }

    public function removeExhibitorAsset(){
        if($this->assetType == "Exhibitor logo"){
            $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('logo');

            if($exhibitorAssetUrl){
                $this->removeExhibitorAssetInStorage($exhibitorAssetUrl);
            }

            Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->update([
                'logo' => null,
            ]);

            $this->exhibitorData['exhibitorLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->exhibitorData['exhibitorLogoDefault'] = true;
        } else {
            $exhibitorAssetUrl = Exhibitors::where('id', $this->exhibitorData['exhibitorId'])->value('banner');
            
            if($exhibitorAssetUrl){
                $this->removeExhibitorAssetInStorage($exhibitorAssetUrl);
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

    public function removeExhibitorAssetInStorage($storageUrl){
        if(Storage::exists($storageUrl)){
            Storage::delete($storageUrl);
        }
    }



    // EDIT EXHIBITOR DETAILS
    public function showEditExhibitorDetails()
    {
        $this->name = $this->exhibitorData['exhibitorName'];
        $this->profile = $this->exhibitorData['exhibitorProfile'];
        $this->stand_number = $this->exhibitorData['exhibitorStandNumber'];
        $this->email_address = $this->exhibitorData['exhibitorEmailAddress'];
        $this->mobile_number = $this->exhibitorData['exhibitorMobileNumber'];
        $this->link = $this->exhibitorData['exhibitorLink'];
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
        $this->email_address = null;
        $this->mobile_number = null;
        $this->link = null;
    }

    public function editExhibitorDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'link' => 'required',
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
            'email_address' => $this->email_address,
            'mobile_number' => $this->mobile_number,
            'link' => $this->link,
        ]);

        $this->exhibitorData['exhibitorName'] = $this->name;
        $this->exhibitorData['exhibitorProfile'] = $this->profile;
        $this->exhibitorData['exhibitorStandNumber'] = $this->stand_number;
        $this->exhibitorData['exhibitorEmailAddress'] = $this->email_address;
        $this->exhibitorData['exhibitorMobileNumber'] = $this->mobile_number;
        $this->exhibitorData['exhibitorLink'] = $this->link;

        $this->resetEditExhibitorDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Exhibitor details updated succesfully!',
            'text' => "",
        ]);
    }
}
