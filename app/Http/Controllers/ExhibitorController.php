<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteExhibitor;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Media;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;

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

    public function apiEventExhibitorDetail($apiCode, $eventCategory, $eventId, $attendeeId, $exhibitorId){
        $exhibitor = Exhibitor::where('id', $exhibitorId)->where('event_id', $eventId)->where('is_active', true)->first();

        if($exhibitor){
            if (AttendeeFavoriteExhibitor::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('exhibitor_id', $exhibitorId)->first()) {
                $is_favorite = true;
            } else {
                $is_favorite = false;
            }

            $data = [
                'exhibitor_id' => $exhibitor->id,
                'logo' => Media::where('id', $exhibitor->logo_media_id)->value('file_url'),
                'name' => $exhibitor->name,
                'stand_number' => $exhibitor->stand_number,
                'profile_html_text' => $exhibitor->profile_html_text,
                'country' => $exhibitor->country,
                'website' => $exhibitor->website,
                'facebook' => $exhibitor->facebook,
                'linkedin' => $exhibitor->linkedin,
                'twitter' => $exhibitor->twitter,
                'instagram' => $exhibitor->instagram,
                'is_favorite' => $is_favorite,
                'favorite_count' => AttendeeFavoriteExhibitor::where('event_id', $eventId)->where('exhibitor_id', $exhibitorId)->count(),
            ];

            return $this->success($data, "Exhibitor details", 200);
        } else {
            return $this->success(null, "Exhibitor doesn't exist", 404);
        }
    }


    public function apiEventExhibitorMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $request->validate([
            'exhibitorId' => 'required', 
            'isFavorite' => 'required|boolean',
        ]);

        if(Exhibitor::find($request->exhibitorId)){
            try {
                $favorite = AttendeeFavoriteExhibitor::where('event_id', $eventId)
                ->where('attendee_id', $attendeeId)
                ->where('exhibitor_id', $request->exhibitorId)
                ->first();

                if ($request->isFavorite) {
                    if (!$favorite) {
                        AttendeeFavoriteExhibitor::create([
                            'event_id' => $eventId,
                            'attendee_id' => $attendeeId,
                            'exhibitor_id' => $request->exhibitorId,
                        ]);
                    }
                } else {
                    if ($favorite) {
                        $favorite->delete();
                    }
                }
        
                $data = [
                    'favorite_count' => AttendeeFavoriteExhibitor::where('event_id', $eventId)
                        ->where('exhibitor_id', $request->exhibitorId)
                        ->count(),
                ];
        
                return $this->success($data, "Exhibitor favorite status updated successfully", 200);
            } catch (\Exception $e) {
                return $this->error(null, "An error occurred while updating the favorite status", 500);
            }
        } else {
            return $this->error(null, "Exhibitor doesn't exist", 404);
        }
    }
}
