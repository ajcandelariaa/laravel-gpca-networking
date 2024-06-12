<?php

namespace App\Http\Livewire;

use App\Enums\MediaEntityTypes;
use App\Enums\MediaUsageUpdateTypes;
use Livewire\Component;
use App\Models\Event as Events;
use App\Models\Feature as Features;
use App\Models\Media as Medias;
use Carbon\Carbon;

class FeatureDetails extends Component
{
    public $event, $featureData;

    public $full_name, $short_name, $edition, $location, $description_html_text, $link, $start_date, $end_date, $editFeatureDetailsForm;

    // EDIT ASSETS
    public $assetType, $editFeatureAssetForm, $image_media_id, $image_placeholder_text;
    public $chooseImageModal, $mediaFileList = array(), $activeSelectedImage;

    protected $listeners = ['editFeatureDetailsConfirmed' => 'editFeatureDetails', 'editFeatureAssetConfirmed' => 'editFeatureAsset'];

    public function mount($eventId, $eventCategory, $featureData)
    {
        $this->event = Events::where('id', $eventId)->where('category', $eventCategory)->first();
        $this->featureData = $featureData;
        $this->mediaFileList = getMediaFileList();
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

    public function resetEditFeatureAssetFields()
    {
        $this->editFeatureAssetForm = false;
        $this->assetType = null;
        $this->image_media_id = null;
        $this->image_placeholder_text = null;
    }

    public function editFeatureAssetConfirmation()
    {
        $this->validate([
            'image_placeholder_text' => 'required'
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
        if ($this->assetType == "Feature Logo") {
            Features::where('id', $this->featureData['featureId'])->update([
                'logo_media_id' => $this->image_media_id,
            ]);

            if ($this->featureData['featureLogo']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::FEATURE_LOGO->value,
                    $this->featureData['featureId'],
                    $this->featureData['featureLogo']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::FEATURE_LOGO->value,
                    $this->featureData['featureId'],
                    $this->featureData['featureLogo']['media_usage_id']
                );
            }

            $this->featureData['featureLogo'] = [
                'media_id' => $this->image_media_id,
                'media_usage_id' => getMediaUsageId($this->image_media_id, MediaEntityTypes::FEATURE_LOGO->value, $this->featureData['featureId']),
                'url' => Medias::where('id', $this->image_media_id)->value('file_url'),
            ];
        } else {
            Features::where('id', $this->featureData['featureId'])->update([
                'banner_media_id' => $this->image_media_id,
            ]);

            if ($this->featureData['featureBanner']['media_id'] != null) {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::REMOVED_THEN_ADD->value,
                    $this->image_media_id,
                    MediaEntityTypes::FEATURE_BANNER->value,
                    $this->featureData['featureId'],
                    $this->featureData['featureBanner']['media_usage_id']
                );
            } else {
                mediaUsageUpdate(
                    MediaUsageUpdateTypes::ADD_ONLY->value,
                    $this->image_media_id,
                    MediaEntityTypes::FEATURE_BANNER->value,
                    $this->featureData['featureId'],
                    $this->featureData['featureBanner']['media_usage_id']
                );
            }
            
            $this->featureData['featureBanner'] = [
                'media_id' => $this->image_media_id,
                'media_usage_id' => getMediaUsageId($this->image_media_id, MediaEntityTypes::FEATURE_BANNER->value, $this->featureData['featureId']),
                'url' => Medias::where('id', $this->image_media_id)->value('file_url'),
            ];
        }

        $this->dispatchBrowserEvent('swal:success', [
            'type' => 'success',
            'message' => $this->assetType . ' updated succesfully!',
            'text' => "",
        ]);

        $this->resetEditFeatureAssetFields();
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


    // EDIT FEATURE DETAILS
    public function showEditFeatureDetails()
    {
        $this->full_name = $this->featureData['featureFullName'];
        $this->short_name = $this->featureData['featureShortName'];
        $this->edition = $this->featureData['featureEdition'];
        $this->location = $this->featureData['featureLocation'];
        $this->description_html_text = $this->featureData['featureDescriptionHTMLText'];
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
        $this->full_name = null;
        $this->short_name = null;
        $this->edition = null;
        $this->location = null;
        $this->description_html_text = null;
        $this->link = null;
        $this->start_date = null;
        $this->end_date = null;
    }

    public function editFeatureDetailsConfirmation()
    {
        $this->validate([
            'full_name' => 'required',
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
            'full_name' => $this->full_name,
            'short_name' => $this->short_name,
            'edition' => $this->edition,
            'location' => $this->location,
            'description_html_text' => $this->description_html_text,
            'link' => $this->link,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);

        $this->featureData['featureFullName'] = $this->full_name;
        $this->featureData['featureShortName'] = $this->short_name;
        $this->featureData['featureEdition'] = $this->edition;
        $this->featureData['featureLocation'] = $this->location;
        $this->featureData['featureDescriptionHTMLText'] = $this->description_html_text;
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
