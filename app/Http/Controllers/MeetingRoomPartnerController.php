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
use Illuminate\Support\Facades\Validator;

class MeetingRoomPartnerController extends Controller
{
    use HttpResponses;

    public function eventMeetingRoomPartnersView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.meeting-room-partners.meeting_room_partners', [
            "pageTitle" => "Meeting room partners",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMeetingRoomPartnerView($eventCategory, $eventId, $meetingRoomPartnerId)
    {
        $meetingRoomPartner = MeetingRoomPartner::with(['event', 'logo', 'banner'])->where('id', $meetingRoomPartnerId)->first();

        if ($meetingRoomPartner) {
            $meetingRoomPartnerData = [
                "meetingRoomPartnerId" => $meetingRoomPartner->id,

                "name" => $meetingRoomPartner->name,
                "location" => $meetingRoomPartner->location,
                "profile_html_text" => $meetingRoomPartner->profile_html_text,

                "logo" => [
                    'media_id' => $meetingRoomPartner->logo_media_id,
                    'media_usage_id' => getMediaUsageId($meetingRoomPartner->logo_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_LOGO->value, $meetingRoomPartner->id),
                    'url' => $meetingRoomPartner->logo->file_url ?? null,
                ],
                "banner" => [
                    'media_id' => $meetingRoomPartner->banner_media_id,
                    'media_usage_id' => getMediaUsageId($meetingRoomPartner->banner_media_id, MediaEntityTypes::MEETING_ROOM_PARTNER_BANNER->value, $meetingRoomPartner->id),
                    'url' => $meetingRoomPartner->banner->file_url ?? null,
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
                "floorplan_link" => $meetingRoomPartner->floorplan_link,

                "is_active" => $meetingRoomPartner->is_active,
                "datetime_added" => Carbon::parse($meetingRoomPartner->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.meeting-room-partners.meeting_room_partner', [
                "pageTitle" => "Meeting Room Partner",
                "eventName" => $meetingRoomPartner->event->full_name,
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
        try {
            $meetingRoomPartners = MeetingRoomPartner::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

            if ($meetingRoomPartners->isEmpty()) {
                return $this->error(null, "No meeting room partners available at the moment.", 404);
            }

            $data = array();
            foreach ($meetingRoomPartners as $meetingRoomPartner) {
                array_push($data, [
                    'id' => $meetingRoomPartner->id,
                    'name' => $meetingRoomPartner->name,
                    'location' => $meetingRoomPartner->location,
                    'logo' => $meetingRoomPartner->logo->file_url ?? null,
                ]);
            }
            return $this->success($data, "Meeting room partner list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the meeting room partner list", 500);
        }
    }

    public function apiEventMeetingRoomPartnerDetail($apiCode, $eventCategory, $eventId, $attendeeId, $meetingRoomPartnerId)
    {
        try {
            $meetingRoomPartner = MeetingRoomPartner::with('logo')->where('id', $meetingRoomPartnerId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$meetingRoomPartner) {
                return $this->error(null, "Meeting room partner doesn't exist", 404);
            }
            
            $floorplanLinks = $meetingRoomPartner->floorplan_link
                ? array_map('trim', explode(',', $meetingRoomPartner->floorplan_link))
                : [];

            $data = [
                'meeting_room_partner_id' => $meetingRoomPartner->id,
                'logo' => $meetingRoomPartner->logo->file_url ?? null,
                'name' => $meetingRoomPartner->name,
                'location' => $meetingRoomPartner->location,
                'profile_html_text' => $meetingRoomPartner->profile_html_text,
                'country' => $meetingRoomPartner->country,
                'contact_person_name' => $meetingRoomPartner->contact_person_name,
                'email_address' => $meetingRoomPartner->email_address,
                'mobile_number' => $meetingRoomPartner->mobile_number,
                'website' => $meetingRoomPartner->website,
                'facebook' => $meetingRoomPartner->facebook,
                'linkedin' => $meetingRoomPartner->linkedin,
                'twitter' => $meetingRoomPartner->twitter,
                'instagram' => $meetingRoomPartner->instagram,
                'floorplan_links' => $floorplanLinks,
                'is_favorite' => AttendeeFavoriteMrp::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('meeting_room_partner_id', $meetingRoomPartnerId)->exists(),
                'favorite_count' => AttendeeFavoriteMrp::where('event_id', $eventId)->where('meeting_room_partner_id', $meetingRoomPartnerId)->count(),
            ];
            return $this->success($data, "Meeting room partner details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the meeting room partner details", 500);
        }
    }


    public function apiEventMeetingRoomPartnerMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'meetingRoomPartnerId' => 'required|exists:meeting_room_partners,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteMrp::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('meeting_room_partner_id', $request->meetingRoomPartnerId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteMrp::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'meeting_room_partner_id' => $request->meetingRoomPartnerId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }
            return $this->success(null, "Meeting Room Partner favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }
}
