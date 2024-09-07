<?php

namespace App\Http\Controllers;

use App\Models\AttendeeFavoriteSession;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Session;
use App\Models\SessionSpeaker;
use App\Models\SessionSpeakerType;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SessionController extends Controller
{
    use HttpResponses;

    public function eventSessionsView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.sessions.sessions', [
            "pageTitle" => "Session",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSessionView($eventCategory, $eventId, $sessionId)
    {
        $session = Session::with(['event', 'feature'])->where('id', $sessionId)->first();

        if ($session) {
            if ($session->feature_id == 0) {
                $category = $session->event->short_name;
            } else {
                if ($session->feature) {
                    $category = $session->feature->short_name;
                } else {
                    $category = "Others";
                }
            }

            if ($session->end_time == "none") {
                $finalEndTime = 'onwards';
            } else {
                $finalEndTime = $session->end_time;
            }


            // FORE SESSION SPEAKERS
            $sessionSpeakerGroup = [];
            $finalSessionSpeakerGroup = [];

            $sessionSpeakerTypes = SessionSpeakerType::where('event_id', $eventId)->where('session_id', $sessionId)->orderBy('datetime_added')->get();

            if ($sessionSpeakerTypes->isNotEmpty()) {
                foreach ($sessionSpeakerTypes as $sessionSpeakerType) {
                    array_push($sessionSpeakerGroup, [
                        'sessionSpeakerTypeId' => $sessionSpeakerType->id,
                        'sessionSpeakerTypeName' => $sessionSpeakerType->name,
                        'speakers' => array(),
                    ]);
                }
            }

            array_push($sessionSpeakerGroup, [
                'sessionSpeakerTypeId' => 0,
                'sessionSpeakerTypeName' => null,
                'speakers' => array(),
            ]);

            $sessionSpeakers = SessionSpeaker::with('speaker.pfp')->where('event_id', $eventId)->where('session_id', $sessionId)->get();
            if ($sessionSpeakers->isNotEmpty()) {
                foreach ($sessionSpeakers as $sessionSpeaker) {

                    foreach ($sessionSpeakerGroup as $sessionSpeakerGroupIndex => $group) {
                        if ($group['sessionSpeakerTypeId'] == $sessionSpeaker['session_speaker_type_id']) {

                            $speakerName = $sessionSpeaker->speaker->salutation . ' ' . $sessionSpeaker->speaker->first_name . ' ' . $sessionSpeaker->speaker->middle_name . ' ' . $sessionSpeaker->speaker->last_name;

                            $speakers = [
                                'sessionSpeakerId' => $sessionSpeaker->id,
                                'speakerId' => $sessionSpeaker->speaker->id,
                                'speakerName' => $speakerName,
                                'speakerPFP' => $sessionSpeaker->speaker->pfp->file_url ?? null,
                            ];

                            array_push($sessionSpeakerGroup[$sessionSpeakerGroupIndex]['speakers'], $speakers);
                        }
                    }
                }

                foreach ($sessionSpeakerGroup as $group) {
                    if ($group['speakers'] != array()) {
                        array_push($finalSessionSpeakerGroup, $group);
                    }
                }
            } else {
                $finalSessionSpeakerGroup = [];
            }

            if ($session->sponsor_id) {
                $sponsor = Sponsor::with(['sponsorType', 'logo'])->where('id', $session->sponsor_id)->where('event_id', $eventId)->where('is_active', true)->first();
                if($sponsor){
                    $sponsorTypeName = $sponsor->sponsorType->name ?? null;
                    $sponsorName = $sponsor->name . ' - ' . $sponsorTypeName;
                    $sessionSponsorLogo = $sponsor->logo->file_url ?? null;
                } else {
                    $sponsorName = null;
                    $sessionSponsorLogo = null;
                }
            } else {
                $sponsorName = null;
                $sessionSponsorLogo = null;
            }

            $sessionData = [
                "sessionId" => $session->id,
                "sessionCategoryName" => $category,
                "sessionFeatureId" => $session->feature_id,

                "sessionDate" => $session->session_date,
                "sessionDateName" => Carbon::parse($session->session_date)->format('F d, Y'),

                "sessionDay" => $session->session_day,
                "sessionType" => $session->session_type,
                "sessionTitle" => $session->title,
                "sessionDescription" => $session->description_html_text,
                "sessionStartTime" => $session->start_time,
                "sessionEndTime" => $finalEndTime,
                "sessionLocation" => $session->location,

                "sessionSponsorLogo" => [
                    'sponsor_id' => $session->sponsor_id,
                    'name' => $sponsorName,
                    'url' => $sessionSponsorLogo,
                ],

                "finalSessionSpeakerGroup" => $finalSessionSpeakerGroup,

                "sessionStatus" => $session->active,
                "sessionDateTimeAdded" => Carbon::parse($session->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.sessions.session', [
                "pageTitle" => "Session",
                "eventName" => $session->event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "sessionData" => $sessionData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }

    public function eventSessionSpeakerTypesView($eventCategory, $eventId, $sessionId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $session = Session::where('id', $sessionId)->first();

        if ($session) {
            return view('admin.event.sessions.session_speaker_types', [
                "pageTitle" => "Session speaker types",
                "eventName" => $event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "sessionId" => $sessionId,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventSessionDetail($apiCode, $eventCategory, $eventId, $attendeeId, $sessionId)
    {
        try {
            $session = Session::with(['feature', 'sponsor.logo'])->where('id', $sessionId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$session) {
                return $this->error(null, "Session doesn't exist", 404);
            }

            $sessionSpeakerTypes = SessionSpeakerType::where('event_id', $eventId)->where('session_id', $sessionId)->orderBy('datetime_added', 'ASC')->get();

            $sessionSpeakerCategorized = array();
            if ($sessionSpeakerTypes->isNotEmpty()) {
                foreach ($sessionSpeakerTypes as $sessionSpeakerType) {
                    $sessionSpeakers = SessionSpeaker::where('event_id', $eventId)->where('session_id', $sessionId)->where('session_speaker_type_id', $sessionSpeakerType->id)->get();

                    if ($sessionSpeakers->isNotEmpty()) {
                        $categorizedSpeakers = array();

                        foreach ($sessionSpeakers as $sessionSpeaker) {
                            $speaker = Speaker::where('id', $sessionSpeaker->speaker_id)->where('event_id', $eventId)->where('is_active', true)->first();

                            if ($speaker) {
                                array_push($categorizedSpeakers, [
                                    'speaker_id' => $speaker->id,
                                    'salutation' => $speaker->salutation,
                                    'first_name' => $speaker->first_name,
                                    'middle_name' => $speaker->middle_name,
                                    'last_name' => $speaker->last_name,
                                    'company_name' => $speaker->company_name,
                                    'job_title' => $speaker->job_title,
                                    'pfp' => $speaker->pfp->file_url ?? null,
                                ]);
                            }
                        }

                        if (count($categorizedSpeakers) > 0) {
                            array_push($sessionSpeakerCategorized, [
                                'speaker_type_name' => $sessionSpeakerType->name,
                                'text_color' => $sessionSpeakerType->text_color ?? "#ffffff",
                                'background_color' => $sessionSpeakerType->background_color ?? "#000000",
                                'speakers' => $categorizedSpeakers,
                            ]);
                        }
                    }
                }
            }

            if($session->end_time == "none"){
                $sessionEndTime = "";
            } else {
                $sessionEndTime = $session->end_time;
            }

            $data = [
                'session_id' => $session->id,
                'title' => $session->title,
                'description_html_text' => $session->description_html_text,
                'start_time' => $session->start_time,
                'end_time' => $sessionEndTime,
                'location' => $session->location,
                'session_date' => Carbon::parse($session->session_date)->format('F d, Y'),
                'session_week_day' => Carbon::parse($session->session_date)->format('l'),
                'session_day' => $session->session_day,
                'session_type' => $session->session_type,
                'sponsored_by' => $session->sponsor->logo->file_url ?? null,
                'is_favorite' => AttendeeFavoriteSession::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('session_id', $sessionId)->exists(),
                'favorite_count' => AttendeeFavoriteSession::where('event_id', $eventId)->where('session_id', $sessionId)->count(),
                'sessionSpeakerCategorized' => $sessionSpeakerCategorized,
            ];

            return $this->success($data, "Session details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the session details", 500);
        }
    }


    public function apiEventSessionMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'sessionId' => 'required|exists:sessions,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteSession::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('session_id', $request->sessionId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteSession::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'session_id' => $request->sessionId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }
            return $this->success(null, "Session favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }
}
