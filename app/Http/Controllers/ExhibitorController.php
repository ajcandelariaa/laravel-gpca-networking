<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteExhibitor;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Media;
use App\Models\MeetingRoomPartner;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ExhibitorController extends Controller
{
    use HttpResponses;

    public function eventExhibitorsView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.exhibitors.exhibitors', [
            "pageTitle" => "Exhibitors",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventExhibitorView($eventCategory, $eventId, $exhibitorId)
    {
        $exhibitor = Exhibitor::with(['event', 'logo', 'banner'])->where('id', $exhibitorId)->first();

        if ($exhibitor) {
            $exhibitorData = [
                "exhibitorId" => $exhibitor->id,

                "name" => $exhibitor->name,
                "stand_number" => $exhibitor->stand_number,
                "profile_html_text" => $exhibitor->profile_html_text,

                "logo" => [
                    'media_id' => $exhibitor->logo_media_id,
                    'media_usage_id' => getMediaUsageId($exhibitor->logo_media_id, MediaEntityTypes::EXHIBITOR_LOGO->value, $exhibitor->id),
                    'url' => $exhibitor->logo->file_url ?? null,
                ],
                "banner" => [
                    'media_id' => $exhibitor->banner_media_id,
                    'media_usage_id' => getMediaUsageId($exhibitor->banner_media_id, MediaEntityTypes::EXHIBITOR_BANNER->value, $exhibitor->id),
                    'url' => $exhibitor->banner->file_url ?? null,
                ],

                "country" => $exhibitor->country,
                "contact_person_name" => $exhibitor->contact_person_name,
                "email_address" => $exhibitor->email_address,
                "mobile_number" => $exhibitor->mobile_number,
                "website" => $exhibitor->website,
                "facebook" => $exhibitor->facebook,
                "linkedin" => $exhibitor->linkedin,
                "twitter" => $exhibitor->twitter,
                "instagram" => $exhibitor->instagram,
                "floorplan_link" => $exhibitor->floorplan_link,

                "is_active" => $exhibitor->is_active,
                "datetime_added" => Carbon::parse($exhibitor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.exhibitors.exhibitor', [
                "pageTitle" => "Exhibitor",
                "eventName" => $exhibitor->event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "exhibitorData" => $exhibitorData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventExhibitors($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $exhibitors = Exhibitor::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

            if ($exhibitors->isEmpty()) {
                return $this->error(null, "No exhibitors available at the moment.", 404);
            }

            $data = array();
            foreach ($exhibitors as $exhibitor) {
                array_push($data, [
                    'id' => $exhibitor->id,
                    'name' => $exhibitor->name,
                    'stand_number' => $exhibitor->stand_number ?? $exhibitor->website,
                    'logo' => $exhibitor->logo->file_url ?? null,
                ]);
            }
            return $this->success($data, "Exhibitors list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the exhibitor list", 500);
        }
    }

    public function apiEventExhibitorDetail($apiCode, $eventCategory, $eventId, $attendeeId, $exhibitorId)
    {
        try {
            $exhibitor = Exhibitor::with('logo')->where('id', $exhibitorId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$exhibitor) {
                return $this->error(null, "Exhibitor doesn't exist", 404);
            }

            $floorplanLinks = $exhibitor->stand_number
                ? array_map('trim', explode(',', $exhibitor->stand_number))
                : [];

            $data = [
                'exhibitor_id' => $exhibitor->id,
                'logo' => $exhibitor->logo->file_url ?? null,
                'name' => $exhibitor->name,
                'stand_number' => $exhibitor->stand_number,
                'profile_html_text' => $exhibitor->profile_html_text,
                'country' => $exhibitor->country,
                'contact_person_name' => $exhibitor->contact_person_name,
                'email_address' => $exhibitor->email_address,
                'mobile_number' => $exhibitor->mobile_number,
                'website' => $exhibitor->website,
                'facebook' => $exhibitor->facebook,
                'linkedin' => $exhibitor->linkedin,
                'twitter' => $exhibitor->twitter,
                'instagram' => $exhibitor->instagram,
                'floorplan_links' => $floorplanLinks,
                'is_favorite' => AttendeeFavoriteExhibitor::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('exhibitor_id', $exhibitorId)->exists(),
                'favorite_count' => AttendeeFavoriteExhibitor::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->count(),
            ];
            return $this->success($data, "Exhibitor details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the exhibitor details", 500);
        }
    }


    public function apiEventExhibitorMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'exhibitorId' => 'required|exists:exhibitors,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteExhibitor::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('exhibitor_id', $request->exhibitorId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteExhibitor::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'exhibitor_id' => $request->exhibitorId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }
            return $this->success(null, "Exhibitor favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }

    public function apiEventExhibitorMeetingRoomPartners($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $finalData = array();
            $exhibitorsData = array();
            $meetingRoomPartnersData = array();

            $exhibitors = Exhibitor::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
            $meetingRoomPartners = MeetingRoomPartner::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();


            if ($exhibitors->isEmpty() && $meetingRoomPartners->isEmpty()) {
                return $this->error(null, "No exhibitors and meeting room partners available at the moment.", 404);
            }

            if ($exhibitors->isNotEmpty()) {
                foreach ($exhibitors as $exhibitor) {
                    array_push($exhibitorsData, [
                        'id' => $exhibitor->id,
                        'name' => $exhibitor->name,
                        'stand_number' => $exhibitor->stand_number ?? $exhibitor->website,
                        'logo' => $exhibitor->logo->file_url ?? null,
                    ]);
                }
            }

            if ($meetingRoomPartners->isNotEmpty()) {
                foreach ($meetingRoomPartners as $meetingRoomPartner) {
                    array_push($meetingRoomPartnersData, [
                        'id' => $meetingRoomPartner->id,
                        'name' => $meetingRoomPartner->name,
                        'location' => $meetingRoomPartner->location,
                        'logo' => $meetingRoomPartner->logo->file_url ?? null,
                    ]);
                }
            }

            $finalData = [
                'exhibitors' => $exhibitorsData,
                'meeting_room_partners' => $meetingRoomPartnersData,
            ];

            return $this->success($finalData, "Exhibitors & Meeting room partners list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the exhibitor and meeting room partner list", 500);
        }
    }
}
