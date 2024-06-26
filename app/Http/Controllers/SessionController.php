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
use App\Models\SponsorType;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $session = Session::where('id', $sessionId)->first();

        if ($session) {
            if ($session->feature_id == 0) {
                $category = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $session->feature_id)->first();
                if ($feature) {
                    $category = $feature->short_name;
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
            $sessionSpeakerGroup = array();
            $finalSessionSpeakerGroup = array();

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

            $sessionSpeakers = SessionSpeaker::where('event_id', $eventId)->where('session_id', $sessionId)->get();
            if ($sessionSpeakers->isNotEmpty()) {
                foreach ($sessionSpeakers as $sessionSpeaker) {

                    foreach ($sessionSpeakerGroup as $sessionSpeakerGroupIndex => $group) {
                        if ($group['sessionSpeakerTypeId'] == $sessionSpeaker['session_speaker_type_id']) {

                            $speaker = Speaker::where('event_id', $eventId)->where('id', $sessionSpeaker->speaker_id)->first();
                            $speakerName = $speaker->salutation . ' ' . $speaker->first_name . ' ' . $speaker->middle_name . ' ' . $speaker->last_name;

                            if ($speaker->pfp) {
                                $speakerPFP = Storage::url($speaker->pfp);
                            } else {
                                $speakerPFP = asset('assets/images/pfp-placeholder.jpg');
                            }

                            $speakers = [
                                'sessionSpeakerId' => $sessionSpeaker->id,
                                'speakerId' => $speaker->id,
                                'speakerName' => $speakerName,
                                'speakerPFP' => $speakerPFP,
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
                $finalSessionSpeakerGroup = array();
            }

            if($session->sponsor_id){
                $sponsor = Sponsor::where('id', $session->sponsor_id)->where('event_id', $eventId)->where('is_active', true)->first();
                $sponsorTypeName = SponsorType::where('id', $sponsor->sponsor_type_id)->value('name');
                $sponsorName = $sponsor->name . ' - ' . $sponsorTypeName;
                $sessionSponsorLogo = Media::where('id', $sponsor->logo_media_id)->value('file_url');
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
                "sessionDescription" => $session->description,
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
                "eventName" => $event->full_name,
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
                "eventName" => $event->name,
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
    public function apiEventSessions($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $sessions = Session::where('event_id', $eventId)->where('is_active', true)->orderBy('session_date', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($sessions->isEmpty()) {
            return $this->success(null, "There are no session yet", 200);
        } else {
            $data = array();

            $categorizedSessionsByDate = array();
            $storeDatesCategoryTemp = [];


            // GET THE DATES FIRST
            $storeDatesCategoryTemp = [];
            foreach ($sessions as $session) {
                if ($session->feature_id == 0) {
                    $date = $session->session_date;
                    if (!isset($storeDatesCategoryTemp[$date])) {
                        $storeDatesCategoryTemp[$date] = true;
                    }
                }
            }
            $uniqueDates = array_keys($storeDatesCategoryTemp);


            foreach ($uniqueDates as $uniqueDate) {
                $sessionsTemp = array();
                foreach ($sessions as $session) {
                    if ($session->feature_id == 0) {
                        if ($session->session_date == $uniqueDate) {
                            $getSpeakersHeadshot = [];

                            $sessionSpeakersTemp = SessionSpeaker::where('event_id', $eventId)->where('session_id', $session->id)->get();

                            if ($sessionSpeakersTemp->isNotEmpty()) {
                                foreach ($sessionSpeakersTemp as $sessionSpeakerTemp) {
                                    $speakerPFPMediaId = Speaker::where('event_id', $eventId)->where('id', $sessionSpeakerTemp->speaker_id)->value('pfp_media_id');
                                    $getSpeakersHeadshot[] = Media::where('id', $speakerPFPMediaId)->value('file_url');
                                }
                            }
                            array_push($sessionsTemp, [
                                'session_id' => $session->id,
                                'start_time' => $session->start_time,
                                'end_time' => $session->end_time,
                                'title' => $session->title,
                                'speakers_mini_headshot' => $getSpeakersHeadshot,
                            ]);
                        }
                    }
                }

                $dateTemp = Carbon::parse($uniqueDate);
                $formattedDate = $dateTemp->format('D d M');
                array_push($categorizedSessionsByDate, [
                    'sessions_date' => $formattedDate,
                    'sessions' => $sessionsTemp,
                ]);
            }

            $startDate = Carbon::parse($event->event_start_date);
            $endDate = Carbon::parse($event->event_end_date);

            if ($startDate->format('F') === $endDate->format('F')) {
                $formattedDate = $startDate->format('F d') . '-' . $endDate->format('d Y');
            } else {
                $formattedDate = $startDate->format('F d') . '-' . $endDate->format('F d Y');
            }

            array_push($data, [
                'program_name' => $event->short_name,
                'program_banner' => Media::where('id', $event->event_banner_media_id)->value('file_url'),
                'program_date' => $formattedDate,
                'session_dates' => $categorizedSessionsByDate,
            ]);



            if ($features->isNotEmpty()) {
                foreach ($features as $feature) {
                    $categorizedSessionsByDate = array();
                    $storeDatesCategoryTemp = [];


                    // GET THE DATES FIRST
                    $storeDatesCategoryTemp = [];
                    foreach ($sessions as $session) {
                        if ($session->feature_id == $feature->id) {
                            $date = $session->session_date;
                            if (!isset($storeDatesCategoryTemp[$date])) {
                                $storeDatesCategoryTemp[$date] = true;
                            }
                        }
                    }
                    $uniqueDates = array_keys($storeDatesCategoryTemp);


                    foreach ($uniqueDates as $uniqueDate) {
                        $sessionsTemp = array();
                        foreach ($sessions as $session) {
                            if ($session->feature_id == $feature->id) {
                                if ($session->session_date == $uniqueDate) {
                                    $getSpeakersHeadshot = [];

                                    $sessionSpeakersTemp = SessionSpeaker::where('event_id', $eventId)->where('session_id', $session->id)->get();

                                    if ($sessionSpeakersTemp->isNotEmpty()) {
                                        foreach ($sessionSpeakersTemp as $sessionSpeakerTemp) {
                                            $speakerPFPMediaId = Speaker::where('event_id', $eventId)->where('id', $sessionSpeakerTemp->speaker_id)->value('pfp_media_id');
                                            $getSpeakersHeadshot[] = Media::where('id', $speakerPFPMediaId)->value('file_url');
                                        }
                                    }
                                    array_push($sessionsTemp, [
                                        'session_id' => $session->id,
                                        'start_time' => $session->start_time,
                                        'end_time' => $session->end_time,
                                        'title' => $session->title,
                                        'speakers_mini_headshot' => $getSpeakersHeadshot,
                                    ]);
                                }
                            }
                        }

                        $dateTemp = Carbon::parse($uniqueDate);
                        $formattedDate = $dateTemp->format('D d M');
                        array_push($categorizedSessionsByDate, [
                            'sessions_date' => $formattedDate,
                            'sessions' => $sessionsTemp,
                        ]);
                    }

                    $startDate = Carbon::parse($event->event_start_date);
                    $endDate = Carbon::parse($event->event_end_date);

                    if ($startDate->format('F') === $endDate->format('F')) {
                        $formattedDate = $startDate->format('F d') . '-' . $endDate->format('d Y');
                    } else {
                        $formattedDate = $startDate->format('F d') . '-' . $endDate->format('F d Y');
                    }

                    if(count($categorizedSessionsByDate) > 0){
                        array_push($data, [
                            'program_name' => $feature->short_name,
                            'program_banner' => Media::where('id', $feature->banner_media_id)->value('file_url'),
                            'program_date' => $formattedDate,
                            'session_dates' => $categorizedSessionsByDate,
                        ]);
                    }
                }
            }

            return $this->success($data, "Sessions list", 200);
        }
    }

    public function apiEventSessionDetail($apiCode, $eventCategory, $eventId, $attendeeId, $sessionId){
        $session = Session::where('id', $sessionId)->where('event_id', $eventId)->where('is_active', true)->first();

        if($session){
            $sessionSpeakerTypes = SessionSpeakerType::where('event_id', $eventId)->where('session_id', $sessionId)->orderBy('datetime_added', 'ASC')->get();

            if($sessionSpeakerTypes->isNotEmpty()){
                $sessionSpeakerCategorized = array();
                foreach($sessionSpeakerTypes as $sessionSpeakerType){
                    $sessionSpeakers = SessionSpeaker::where('event_id', $eventId)->where('session_id', $sessionId)->where('session_speaker_type_id', $sessionSpeakerType->id)->get();
                    if($sessionSpeakers->isNotEmpty()){
                        $categorizedSpeakers = array();
                        foreach($sessionSpeakers as $sessionSpeaker){
                            $speaker = Speaker::where('id', $sessionSpeaker->speaker_id)->where('event_id', $eventId)->where('is_active', true)->first();

                            if($speaker){
                                array_push($categorizedSpeakers, [
                                    'speaker_id' => $speaker->id,
                                    'salutation' => $speaker->salutation,
                                    'first_name' => $speaker->first_name,
                                    'middle_name' => $speaker->middle_name,
                                    'last_name' => $speaker->last_name,
                                    'company_name' => $speaker->company_name,
                                    'job_title' => $speaker->job_title,
                                    'pfp' => Media::where('id', $speaker->pfp_media_id)->value('file_url'),
                                ]);
                            }
                        }

                        if(count($categorizedSpeakers) > 0){
                            array_push($sessionSpeakerCategorized, [
                                'speaker_type_name' => $sessionSpeakerType->name,
                                'text_color' => $sessionSpeakerType->text_color,
                                'background_color' => $sessionSpeakerType->background_color,
                                'speakers' => $categorizedSpeakers,
                            ]);
                        }
                    }
                }
            }

            if (AttendeeFavoriteSession::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('session_id', $sessionId)->first()) {
                $is_favorite = true;
            } else {
                $is_favorite = false;
            }

            $data = [
                'session_id' => $session->id,
                'title' => $session->title,
                'description_html_text' => $session->description_html_text,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
                'location' => $session->location,
                'session_date' => Carbon::parse($session->session_date)->format('F d, Y'),
                'session_week_day' => Carbon::parse($session->session_date)->format('l'),
                'session_day' => $session->session_day,
                'session_type' => $session->session_type,
                'is_favorite' => $is_favorite,
                'favorite_count' => AttendeeFavoriteSession::where('event_id', $eventId)->where('session_id', $sessionId)->count(),
                'sessionSpeakerCategorized' => $sessionSpeakerCategorized,
            ];

            return $this->success($data, "Session details", 200);
        } else {
            return $this->error(null, "Session doesn't exist", 404);
        }
    }


    public function apiEventSessionMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $request->validate([
            'sessionId' => 'required', 
            'isFavorite' => 'required|boolean',
        ]);

        if(Session::find($request->sessionId)){
            try {
                $favorite = AttendeeFavoriteSession::where('event_id', $eventId)
                ->where('attendee_id', $attendeeId)
                ->where('session_id', $request->sessionId)
                ->first();

                if ($request->isFavorite) {
                    if (!$favorite) {
                        AttendeeFavoriteSession::create([
                            'event_id' => $eventId,
                            'attendee_id' => $attendeeId,
                            'session_id' => $request->sessionId,
                        ]);
                    }
                } else {
                    if ($favorite) {
                        $favorite->delete();
                    }
                }
        
                $data = [
                    'favorite_count' => AttendeeFavoriteSession::where('event_id', $eventId)
                        ->where('session_id', $request->sessionId)
                        ->count(),
                ];
        
                return $this->success($data, "Session favorite status updated successfully", 200);
            } catch (\Exception $e) {
                return $this->error(null, "An error occurred while updating the favorite status", 500);
            }
        } else {
            return $this->error(null, "Session doesn't exist", 404);
        }
    }
}
