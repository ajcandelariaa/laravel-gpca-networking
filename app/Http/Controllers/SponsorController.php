<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Sponsor;
use App\Models\SponsorType;
use Carbon\Carbon;

class SponsorController extends Controller
{
    public function eventSponsorsView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');
        
        return view('admin.event.sponsors.sponsors', [
            "pageTitle" => "Sponsors",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSponsorTypesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');
        
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
            if($sponsor->feature_id == 0){
                $categoryName = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $sponsor->feature_id)->first();
                if($feature){
                    $categoryName = $feature->short_name;
                } else {
                    $categoryName = "Others";
                }
            }

            $sponsorType = SponsorType::where('event_id', $event->id)->where('id', $sponsor->sponsor_type_id)->first();
            if($sponsorType){
                $typeName = $sponsorType->name;
            } else {
                $typeName = "N/A";
            }

            $sponsorData = [
                "sponsorId" => $sponsor->id,

                "categoryName" => $categoryName,
                "feature_id" => $sponsor->feature_id,
                "typeName" => $typeName,
                "sponsor_type_id" => $sponsor->sponsor_type_id,

                "name" => $sponsor->name,
                "profile_html_text" => $sponsor->profile_html_text,

                "logo" => [
                    'media_id' => $sponsor->logo_media_id,
                    'media_usage_id' => getMediaUsageId($sponsor->logo_media_id, MediaEntityTypes::SPONSOR_LOGO->value, $sponsor->id),
                    'url' => Media::where('id', $sponsor->logo_media_id)->value('file_url'),
                ],
                "banner" => [
                    'media_id' => $sponsor->banner_media_id,
                    'media_usage_id' => getMediaUsageId($sponsor->banner_media_id, MediaEntityTypes::SPONSOR_BANNER->value, $sponsor->id),
                    'url' => Media::where('id', $sponsor->banner_media_id)->value('file_url'),
                ],

                "country" => $sponsor->country,
                "contact_person_name" => $sponsor->contact_person_name,
                "email_address" => $sponsor->email_address,
                "mobile_number" => $sponsor->mobile_number,
                "website" => $sponsor->website,
                "facebook" => $sponsor->facebook,
                "linkedin" => $sponsor->linkedin,
                "twitter" => $sponsor->twitter,
                "instagram" => $sponsor->instagram,

                "is_active" => $sponsor->is_active,
                "datetime_added" => Carbon::parse($sponsor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.sponsors.sponsor', [
                "pageTitle" => "Sponsor",
                "eventName" => $event->full_name,
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
