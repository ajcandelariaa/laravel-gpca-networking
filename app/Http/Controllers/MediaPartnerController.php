<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Media;
use App\Models\MediaPartner;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class MediaPartnerController extends Controller
{
    public function eventMediaPartnersView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.media-partners.media_partners', [
            "pageTitle" => "Media partners",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMediaPartnerView($eventCategory, $eventId, $mediaPartnerId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $mediaPartner = MediaPartner::where('id', $mediaPartnerId)->first();

        if($mediaPartner){
            $mediaPartnerData = [
                "mediaPartnerId" => $mediaPartner->id,

                "name" => $mediaPartner->name,
                "profile" => $mediaPartner->profile,

                "logo" => [
                    'media_id' => $mediaPartner->logo_media_id,
                    'media_usage_id' => getMediaUsageId($mediaPartner->logo_media_id, MediaEntityTypes::MEDIA_PARTNER_LOGO->value, $mediaPartner->id),
                    'url' => Media::where('id', $mediaPartner->logo_media_id)->value('file_url'),
                ],
                "banner" => [
                    'media_id' => $mediaPartner->banner_media_id,
                    'media_usage_id' => getMediaUsageId($mediaPartner->banner_media_id, MediaEntityTypes::MEDIA_PARTNER_BANNER->value, $mediaPartner->id),
                    'url' => Media::where('id', $mediaPartner->banner_media_id)->value('file_url'),
                ],

                "country" => $mediaPartner->country,
                "contact_person_name" => $mediaPartner->contact_person_name,
                "email_address" => $mediaPartner->email_address,
                "mobile_number" => $mediaPartner->mobile_number,
                "website" => $mediaPartner->website,
                "facebook" => $mediaPartner->facebook,
                "linkedin" => $mediaPartner->linkedin,
                "twitter" => $mediaPartner->twitter,
                "instagram" => $mediaPartner->instagram,

                "is_active" => $mediaPartner->is_active,
                "datetime_added" => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
            ];
            
            return view('admin.event.media-partners.media_partner', [
                "pageTitle" => "Media Partner",
                "eventName" => $event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "mediaPartnerData" => $mediaPartnerData,
            ]);
        } else {
            abort(404, 'Data not found'); 
        }
    }

    public function getListOfMediaPartners() {
        return response()->json();
    }
}
