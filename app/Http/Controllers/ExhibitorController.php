<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Media;
use App\Traits\HttpResponses;
use Carbon\Carbon;

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
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $exhibitor = Exhibitor::where('id', $exhibitorId)->first();

        if ($exhibitor) {
            $exhibitorData = [
                "exhibitorId" => $exhibitor->id,

                "name" => $exhibitor->name,
                "stand_number" => $exhibitor->stand_number,
                "profile_html_text" => $exhibitor->profile_html_text,

                "logo" => [
                    'media_id' => $exhibitor->logo_media_id,
                    'media_usage_id' => getMediaUsageId($exhibitor->logo_media_id, MediaEntityTypes::EXHIBITOR_LOGO->value, $exhibitor->id),
                    'url' => Media::where('id', $exhibitor->logo_media_id)->value('file_url'),
                ],
                "banner" => [
                    'media_id' => $exhibitor->banner_media_id,
                    'media_usage_id' => getMediaUsageId($exhibitor->banner_media_id, MediaEntityTypes::EXHIBITOR_BANNER->value, $exhibitor->id),
                    'url' => Media::where('id', $exhibitor->banner_media_id)->value('file_url'),
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

                "is_active" => $exhibitor->is_active,
                "datetime_added" => Carbon::parse($exhibitor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.exhibitors.exhibitor', [
                "pageTitle" => "Exhibitor",
                "eventName" => $event->full_name,
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
        $exhibitors = Exhibitor::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($exhibitors->isEmpty()) {
            return $this->success(null, "There are no exhibitor yet", 200);
        } else {
            $data = array();
            foreach ($exhibitors as $exhibitor) {
                array_push($data, [
                    'id' => $exhibitor->id,
                    'name' => $exhibitor->name,
                    'stand_number' => $exhibitor->stand_number,
                    'logo' => Media::where('id', $exhibitor->logo_media_id)->value('file_url'),
                ]);
            }
            return $this->success($data, "Exhibitor list", 200);
        }
    }
}
