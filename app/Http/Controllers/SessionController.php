<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Session;
use App\Models\SessionSpeaker;
use App\Models\SessionSpeakerType;
use App\Models\Speaker;
use App\Traits\HttpResponses;
use Carbon\Carbon;
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
}
