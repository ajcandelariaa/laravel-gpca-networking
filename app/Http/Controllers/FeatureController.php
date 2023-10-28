<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feature;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeatureController extends Controller
{
    public function eventFeaturesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.features.features', [
            "pageTitle" => "Feature",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventFeatureView($eventCategory, $eventId, $featureId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $feature = Feature::where('id', $featureId)->first();

        if ($feature) {
            if ($feature->logo) {
                $featureLogo = Storage::url($feature->logo);
                $featureLogoDefault = false;
            } else {
                $featureLogo = asset('assets/images/logo-placeholder.jpg');
                $featureLogoDefault = true;
            }

            if ($feature->banner) {
                $featureBanner = Storage::url($feature->banner);
                $featureBannerDefault = false;
            } else {
                $featureBanner = asset('assets/images/banner-placeholder.jpg');
                $featureBannerDefault = true;
            }

            if ($feature->image) {
                $featureImage = Storage::url($feature->image);
                $featureImageDefault = false;
            } else {
                $featureImage = asset('assets/images/feature-image-placeholder.jpg');
                $featureImageDefault = true;
            }

            $formattedDate =  Carbon::parse($feature->start_date)->format('d M Y') . ' - ' . Carbon::parse($feature->end_date)->format('d M Y');

            $featureData = [
                "featureId" => $feature->id,
                "featureName" => $feature->name,
                "featureShortName" => $feature->short_name,
                "featureTagline" => $feature->tagline,
                "featureLocation" => $feature->location,
                "featureShortDescription" => $feature->short_description,
                "featureLongDescription" => $feature->long_description,
                "featureLink" => $feature->link,
                "featureStartDate" => $feature->start_date,
                "featureEndDate" => $feature->end_date,
                "featureFormattedDate" => $formattedDate,
                "featureLogo" => $featureLogo,
                "featureLogoDefault" => $featureLogoDefault,
                "featureBanner" => $featureBanner,
                "featureBannerDefault" => $featureBannerDefault,
                "featureImage" => $featureImage,
                "featureImageDefault" => $featureImageDefault,
                "featureStatus" => $feature->active,
                "featureDateTimeAdded" => Carbon::parse($feature->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.features.feature', [
                "pageTitle" => "Feature",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "featureData" => $featureData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }
}
