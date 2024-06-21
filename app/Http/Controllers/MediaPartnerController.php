<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Media;
use App\Models\MediaPartner;
use App\Traits\HttpResponses;
use Carbon\Carbon;

class MediaPartnerController extends Controller
{
    use HttpResponses;

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
                "profile_html_text" => $mediaPartner->profile_html_text,

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




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventMediaPartners($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $mediaPartners = MediaPartner::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($mediaPartners->isEmpty()) {
            return $this->success(null, "There are no media partner yet", 200);
        } else {
            $data = array();
            foreach ($mediaPartners as $mediaPartner) {
                array_push($data, [
                    'id' => $mediaPartner->id,
                    'name' => $mediaPartner->name,
                    'website' => $mediaPartner->website,
                    'logo' => Media::where('id', $mediaPartner->logo_media_id)->value('file_url'),
                ]);
            }
            return $this->success($data, "Media partner list", 200);
        }
    }
}
