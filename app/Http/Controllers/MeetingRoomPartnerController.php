<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Media;
use App\Models\MeetingRoomPartner;
use App\Traits\HttpResponses;
use Carbon\Carbon;

class MeetingRoomPartnerController extends Controller
{
    use HttpResponses;

    public function eventMeetingRoomPartnersView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.meeting-room-partners.meeting_room_partners', [
            "pageTitle" => "Meeting room partners",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMeetingRoomPartnerView($eventCategory, $eventId, $meetingRoomPartnerId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $meetingRoomPartner = MeetingRoomPartner::where('id', $meetingRoomPartnerId)->first();

        if($meetingRoomPartner){
            $meetingRoomPartnerData = [
                "meetingRoomPartnerId" => $meetingRoomPartner->id,

                "name" => $meetingRoomPartner->name,
                "location" => $meetingRoomPartner->location,
                "profile_html_text" => $meetingRoomPartner->profile_html_text,

                "logo" => [
                    'media_id' => $meetingRoomPartner->logo_media_id,
                    'media_usage_id' => getMediaUsageId($meetingRoomPartner->logo_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_LOGO->value, $meetingRoomPartner->id),
                    'url' => Media::where('id', $meetingRoomPartner->logo_media_id)->value('file_url'),
                ],
                "banner" => [
                    'media_id' => $meetingRoomPartner->banner_media_id,
                    'media_usage_id' => getMediaUsageId($meetingRoomPartner->banner_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_BANNER->value, $meetingRoomPartner->id),
                    'url' => Media::where('id', $meetingRoomPartner->banner_media_id)->value('file_url'),
                ],

                "country" => $meetingRoomPartner->country,
                "contact_person_name" => $meetingRoomPartner->contact_person_name,
                "email_address" => $meetingRoomPartner->email_address,
                "mobile_number" => $meetingRoomPartner->mobile_number,

                "website" => $meetingRoomPartner->website,
                "facebook" => $meetingRoomPartner->facebook,
                "linkedin" => $meetingRoomPartner->linkedin,
                "twitter" => $meetingRoomPartner->twitter,
                "instagram" => $meetingRoomPartner->instagram,

                "is_active" => $meetingRoomPartner->is_active,
                "datetime_added" => Carbon::parse($meetingRoomPartner->datetime_added)->format('M j, Y g:i A'),
            ];
            
            return view('admin.event.meeting-room-partners.meeting_room_partner', [
                "pageTitle" => "Meeting Room Partner",
                "eventName" => $event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "meetingRoomPartnerData" => $meetingRoomPartnerData,
            ]);
        } else {
            abort(404, 'Data not found'); 
        }
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventMeetingRoomPartners($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $meetingRoomPartners = MeetingRoomPartner::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($meetingRoomPartners->isEmpty()) {
            return $this->success(null, "There are no meeting room partner yet", 200);
        } else {
            $data = array();
            foreach ($meetingRoomPartners as $meetingRoomPartner) {
                array_push($data, [
                    'id' => $meetingRoomPartner->id,
                    'name' => $meetingRoomPartner->name,
                    'location' => $meetingRoomPartner->location,
                    'logo' => Media::where('id', $meetingRoomPartner->logo_media_id)->value('file_url'),
                ]);
            }
            return $this->success($data, "Meeting room partner list", 200);
        }
    }
}
