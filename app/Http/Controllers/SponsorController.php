<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feature;
use App\Models\Sponsor;
use App\Models\SponsorType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SponsorController extends Controller
{
    public function eventSponsorsView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.sponsors.sponsors', [
            "pageTitle" => "Sponsors",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSponsorTypesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.sponsors.sponsor_types', [
            "pageTitle" => "Sponsors Type",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }



    public function eventSponsorView($eventCategory, $eventId, $sponsorId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $sponsor = Sponsor::where('id', $sponsorId)->first();

        if ($sponsor) {
            if ($sponsor->logo) {
                $sponsorLogo = Storage::url($sponsor->logo);
                $sponsorLogoDefault = false;
            } else {
                $sponsorLogo = asset('assets/images/logo-placeholder.jpg');
                $sponsorLogoDefault = true;
            }

            if ($sponsor->banner) {
                $sponsorBanner = Storage::url($sponsor->banner);
                $sponsorBannerDefault = false;
            } else {
                $sponsorBanner = asset('assets/images/banner-placeholder.jpg');
                $sponsorBannerDefault = true;
            }

            if($sponsor->feature_id == 0){
                $category = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $sponsor->feature_id)->first();
                if($feature){
                    $category = $feature->short_name;
                } else {
                    $category = "Others";
                }
            }

            $sponsorType = SponsorType::where('event_id', $event->id)->where('id', $sponsor->sponsor_type_id)->first();
            if($sponsorType){
                $type = $sponsorType->name;
            } else {
                $type = "N/A";
            }

            $sponsorData = [
                "sponsorId" => $sponsor->id,
                "sponsorCategoryName" => $category,
                "sponsorFeatureId" => $sponsor->feature_id,
                "sponsorTypeName" => $type,
                "sponsorTypeId" => $sponsor->sponsor_type_id,
                "sponsorName" => $sponsor->name,
                "sponsorEmailAddress" => $sponsor->email_address,
                "sponsorMobileNumber" => $sponsor->mobile_number,
                "sponsorLink" => $sponsor->link,
                "sponsorProfile" => $sponsor->profile,
                "sponsorLogo" => $sponsorLogo,
                "sponsorLogoDefault" => $sponsorLogoDefault,
                "sponsorBanner" => $sponsorBanner,
                "sponsorBannerDefault" => $sponsorBannerDefault,
                "sponsorStatus" => $sponsor->active,
                "sponsorDateTimeAdded" => Carbon::parse($sponsor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.sponsors.sponsor', [
                "pageTitle" => "Sponsor",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "sponsorData" => $sponsorData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }










    public function getListOfSponsors() {
        return response()->json();
    }
    
}
