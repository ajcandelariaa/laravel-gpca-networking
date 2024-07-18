<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Feature;
use Carbon\Carbon;

class FeatureController extends Controller
{
    public function eventFeaturesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');
        
        return view('admin.event.features.features', [
            "pageTitle" => "Feature",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventFeatureView($eventCategory, $eventId, $featureId)
    {
        $feature = Feature::with(['event', 'logo', 'banner'])->where('id', $featureId)->first();

        if ($feature) {
            $formattedDate =  Carbon::parse($feature->start_date)->format('d M Y') . ' - ' . Carbon::parse($feature->end_date)->format('d M Y');
            $featureData = [
                "featureId" => $feature->id,
                "featureFullName" => $feature->full_name,
                "featureShortName" => $feature->short_name,
                "featureEdition" => $feature->edition,
                "featureLocation" => $feature->location,
                "featureDescriptionHTMLText" => $feature->description_html_text,
                "featureLink" => $feature->link,
                "featureStartDate" => $feature->start_date,
                "featureEndDate" => $feature->end_date,
                "featureFormattedDate" => $formattedDate,
                "featurePrimaryBgColor" => $feature->primary_bg_color,
                "featureSecondaryBgColor" => $feature->secondary_bg_color,
                "featurePrimaryTextColor" => $feature->primary_text_color,
                "featureSecondaryTextColor" => $feature->secondary_text_color,
                "featureLogo" => [
                    'media_id' => $feature->logo_media_id,
                    'media_usage_id' => getMediaUsageId($feature->logo_media_id, MediaEntityTypes::FEATURE_LOGO->value, $feature->id),
                    'url' => $feature->logo->file_url ?? null,
                ],
                "featureBanner" => [
                    'media_id' => $feature->banner_media_id,
                    'media_usage_id' => getMediaUsageId($feature->banner_media_id, MediaEntityTypes::FEATURE_BANNER->value, $feature->id),
                    'url' => $feature->banner->file_url ?? null,
                ],
                "featureStatus" => $feature->is_active,
                "featureDateTimeAdded" => Carbon::parse($feature->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.features.feature', [
                "pageTitle" => "Feature",
                "eventName" => $feature->event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "featureData" => $featureData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }
}
