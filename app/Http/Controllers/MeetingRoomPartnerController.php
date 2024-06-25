<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteMrp;
use App\Models\Event;
use App\Models\Media;
use App\Models\MeetingRoomPartner;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function apiEventMeetingRoomPartnerDetail($apiCode, $eventCategory, $eventId, $attendeeId, $meetingRoomPartnerId){
        $meetingRoomPartner = MeetingRoomPartner::where('id', $meetingRoomPartnerId)->where('event_id', $eventId)->where('is_active', true)->first();

        if($meetingRoomPartner){
            if (AttendeeFavoriteMrp::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('meeting_room_partner_id', $meetingRoomPartnerId)->first()) {
                $is_favorite = true;
            } else {
                $is_favorite = false;
            }

            $data = [
                'meeting_room_partner_id' => $meetingRoomPartner->id,
                'logo' => Media::where('id', $meetingRoomPartner->logo_media_id)->value('file_url'),
                'name' => $meetingRoomPartner->name,
                'location' => $meetingRoomPartner->location,
                'profile_html_text' => $meetingRoomPartner->profile_html_text,
                'country' => $meetingRoomPartner->country,
                'website' => $meetingRoomPartner->website,
                'facebook' => $meetingRoomPartner->facebook,
                'linkedin' => $meetingRoomPartner->linkedin,
                'twitter' => $meetingRoomPartner->twitter,
                'instagram' => $meetingRoomPartner->instagram,
                'is_favorite' => $is_favorite,
                'favorite_count' => AttendeeFavoriteMrp::where('event_id', $eventId)->where('meeting_room_partner_id', $meetingRoomPartnerId)->count(),
            ];

            return $this->success($data, "Meeting room partner details", 200);
        } else {
            return $this->success(null, "Meeting room partner doesn't exist", 404);
        }
    }


    public function apiEventMeetingRoomPartnerMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $request->validate([
            'meetingRoomPartnerId' => 'required', 
            'isFavorite' => 'required|boolean',
        ]);

        if(MeetingRoomPartner::find($request->meetingRoomPartnerId)){
            try {
                $favorite = AttendeeFavoriteMrp::where('event_id', $eventId)
                ->where('attendee_id', $attendeeId)
                ->where('meeting_room_partner_id', $request->meetingRoomPartnerId)
                ->first();

                if ($request->isFavorite) {
                    if (!$favorite) {
                        AttendeeFavoriteMrp::create([
                            'event_id' => $eventId,
                            'attendee_id' => $attendeeId,
                            'meeting_room_partner_id' => $request->meetingRoomPartnerId,
                        ]);
                    }
                } else {
                    if ($favorite) {
                        $favorite->delete();
                    }
                }
        
                $data = [
                    'favorite_count' => AttendeeFavoriteMrp::where('event_id', $eventId)
                        ->where('meeting_room_partner_id', $request->meetingRoomPartnerId)
                        ->count(),
                ];
        
                return $this->success($data, "Meeting Room Partner favorite status updated successfully", 200);
            } catch (\Exception $e) {
                return $this->error(null, "An error occurred while updating the favorite status", 500);
            }
        } else {
            return $this->error(null, "Meeting Room Partner doesn't exist", 404);
        }
    }
}
