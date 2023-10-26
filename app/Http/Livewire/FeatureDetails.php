<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Feature as Features;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\WithFileUploads;

class FeatureDetails extends Component
{
    use WithFileUploads;

    public $event, $featureData;

    public $name, $tagline, $location, $short_description, $long_description, $link, $start_date, $end_date, $editFeatureDetailsForm;

    public $assetType, $image, $editFeatureAssetForm;

    protected $listeners = ['editFeatureDetailsConfirmed' => 'editFeatureDetails', 'editFeatureAssetConfirmed' => 'editFeatureAsset', 'removeFeatureAssetConfirmed' => 'removeFeatureAsset'];

    public function mount($eventId, $eventCategory, $featureData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->featureData = $featureData;

        $this->editFeatureAssetForm = false;
        $this->editFeatureDetailsForm = false;
    }

    public function render()
    {
        return view('livewire.event.features.feature-details');
    }




    // EDIT FEATURE ASSET
    public function showEditFeatureAsset($assetType)
    {
        $this->assetType = $assetType;
        $this->editFeatureAssetForm = true;
    }

    public function cancelEditFeatureAsset()
    {
        $this->resetEditFeatureAssetFields();
    }

    public function resetEditFeatureAssetFields()
    {
        $this->editFeatureAssetForm = false;
        $this->assetType = null;
        $this->image = null;
    }

    public function editFeatureAssetConfirmation()
    {

        $this->validate([
            'image' => 'required|mimes:png,jpg,jpeg'
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editFeatureAssetConfirmed",
        ]);
    }

    public function editFeatureAsset()
    {
        $currentYear = $this->event->year;
        $fileName = time() . '-' . $this->image->getClientOriginalName();

        if ($this->assetType == "Feature Logo") {

            if (!$this->featureData['featureLogoDefault']) {
                $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('logo');
                if ($featureAssetUrl) {
                    $this->removeFeatureAssetInStorage($featureAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/features/logo', $fileName);

            Features::where('id', $this->featureData['featureId'])->update([
                'logo' => $path,
            ]);

            $this->featureData['featureLogo'] = Storage::url($path);
            $this->featureData['featureLogoDefault'] = false;
        } else if ($this->assetType == "Feature Banner") {

            if (!$this->featureData['featureBannerDefault']) {
                $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('banner');

                if ($featureAssetUrl) {
                    $this->removeFeatureAssetInStorage($featureAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/features/banner', $fileName);

            Features::where('id', $this->featureData['featureId'])->update([
                'banner' => $path,
            ]);

            $this->featureData['featureBanner'] = Storage::url($path);
            $this->featureData['featureBannerDefault'] = false;
        } else {
            if (!$this->featureData['featureImageDefault']) {
                $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('image');

                if ($featureAssetUrl) {
                    $this->removeFeatureAssetInStorage($featureAssetUrl);
                }
            }

            $path = $this->image->storeAs('public/' . $currentYear . '/' . $this->event->category . '/features/image', $fileName);

            Features::where('id', $this->featureData['featureId'])->update([
                'image' => $path,
            ]);

            $this->featureData['featureImage'] = Storage::url($path);
            $this->featureData['featureImageDefault'] = false;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditFeatureAssetFields();
    }

    public function removeFeatureAssetConfirmation()
    {
        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure you want to remove?',
            'text' => "",
            'buttonConfirmText' => "Yes, remove it!",
            'livewireEmit' => "removeFeatureAssetConfirmed",
        ]);
    }

    public function removeFeatureAsset()
    {
        if ($this->assetType == "Feature Logo") {
            $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('logo');

            if ($featureAssetUrl) {
                $this->removeFeatureAssetInStorage($featureAssetUrl);
            } else {

                dd(true);
            }

            Features::where('id', $this->featureData['featureId'])->update([
                'logo' => null,
            ]);

            $this->featureData['featureLogo'] = asset('assets/images/logo-placeholder.jpg');
            $this->featureData['featureLogoDefault'] = true;
        } else if ($this->assetType == "Feature Banner") {
            $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('banner');

            if ($featureAssetUrl) {
                $this->removeFeatureAssetInStorage($featureAssetUrl);
            }

            Features::where('id', $this->featureData['featureId'])->update([
                'banner' => null,
            ]);

            $this->featureData['featureBanner'] = asset('assets/images/banner-placeholder.jpg');
            $this->featureData['featureBannerDefault'] = true;
        } else {
            $featureAssetUrl = Features::where('id', $this->featureData['featureId'])->value('image');

            if ($featureAssetUrl) {
                $this->removeFeatureAssetInStorage($featureAssetUrl);
            }

            Features::where('id', $this->featureData['featureId'])->update([
                'image' => null,
            ]);

            $this->featureData['featureImage'] = asset('assets/images/feature-image-placeholder.jpg');
            $this->featureData['featureImageDefault'] = true;
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' removed succesfully!',
            'text' => "",
        ]);

        $this->resetEditFeatureAssetFields();
    }

    public function removeFeatureAssetInStorage($storageUrl)
    {
        if (Storage::exists($storageUrl)) {
            Storage::delete($storageUrl);
        }
    }



    // EDIT FEATURE DETAILS
    public function showEditFeatureDetails()
    {
        $this->name = $this->featureData['featureName'];
        $this->tagline = $this->featureData['featureTagline'];
        $this->location = $this->featureData['featureLocation'];
        $this->short_description = $this->featureData['featureShortDescription'];
        $this->long_description = $this->featureData['featureLongDescription'];
        $this->link = $this->featureData['featureLink'];
        $this->start_date = $this->featureData['featureStartDate'];
        $this->end_date = $this->featureData['featureEndDate'];
        $this->editFeatureDetailsForm = true;
    }

    public function cancelEditFeatureDetails()
    {
        $this->resetEditFeatureDetailsFields();
    }

    public function resetEditFeatureDetailsFields()
    {
        $this->editFeatureDetailsForm = false;
        $this->name = null;
        $this->tagline = null;
        $this->location = null;
        $this->short_description = null;
        $this->long_description = null;
        $this->link = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function editFeatureDetailsConfirmation()
    {
        $this->validate([
            'name' => 'required',
            'location' => 'required',
            'link' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $this->dispatchBrowserEvent('swal:confirmation', [
            'type' => 'warning',
            'message' => 'Are you sure?',
            'text' => "",
            'buttonConfirmText' => "Yes, update it!",
            'livewireEmit' => "editFeatureDetailsConfirmed",
        ]);
    }

    public function editFeatureDetails()
    {
        Features::where('id', $this->featureData['featureId'])->update([
            'name' => $this->name,
            'tagline' => $this->tagline,
            'location' => $this->location,
            'short_description' => $this->short_description,
            'long_description' => $this->long_description,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->featureData['featureName'] = $this->name;
        $this->featureData['featureTagline'] = $this->tagline;
        $this->featureData['featureLocation'] = $this->location;
        $this->featureData['featureShortDescription'] = $this->short_description;
        $this->featureData['featureLongDescription'] = $this->long_description;
        $this->featureData['featureLink'] = $this->link;
        $this->featureData['featureStartDate'] = $this->start_date;
        $this->featureData['featureEndDate'] = $this->end_date;
        $this->featureData['featureFormattedDate'] =  Carbon::parse($this->start_date)->format('d M Y') . ' - ' . Carbon::parse($this->end_date)->format('d M Y');

        $this->resetEditFeatureDetailsFields();

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => 'Feature details updated succesfully!',
            'text' => "",
        ]);
    }
}
