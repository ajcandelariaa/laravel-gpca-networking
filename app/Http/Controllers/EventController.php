<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Feature;
use App\Models\Media;
use App\Models\MediaPartner;
use App\Models\MeetingRoomPartner;
use App\Models\Session;
use App\Models\SessionSpeaker;
use App\Models\Speaker;
use App\Models\SpeakerType;
use App\Models\Sponsor;
use App\Models\SponsorType;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    use HttpResponses;
    // =========================================================
    //                       RENDER VIEWS
    // =========================================================

    public function mainDashboardView()
    {
        return view('admin.home.dashboard', [
            "pageTitle" => "Dashboard"
        ]);
    }

    public function eventsView()
    {
        $finalEvents = array();
        $events = Event::orderBy('event_start_date', 'desc')->get();

        if ($events->isNotEmpty()) {
            foreach ($events as $event) {
                $eventFormattedDate =  Carbon::parse($event->event_start_date)->format('d M Y') . ' - ' . Carbon::parse($event->event_end_date)->format('d M Y');
                $eventLogoUrl = Media::where('id', $event->event_logo_media_id)->first()->value('file_url');
                array_push($finalEvents, [
                    'eventId' => $event->id,
                    'eventLogo' => $eventLogoUrl,
                    'eventName' => $event->full_name,
                    'eventCategory' => $event->category,
                    'eventLocation' => $event->location,
                    'eventDate' => $eventFormattedDate,
                ]);
            }
        }

        return view('admin.home.events', [
            "pageTitle" => "Events",
            "finalEvents" => $finalEvents,
        ]);
    }

    public function addEventView()
    {
        return view('admin.home.add.add_event', [
            "pageTitle" => "Add event",
        ]);
    }

    public function eventDashboardView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');
        return view('admin.event.dashboard.dashboard', [
            "pageTitle" => "Dashboard",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventDetailsView($eventCategory, $eventId)
    {
        $event = Event::where('id', $eventId)->first();

        $finalEventStartDate = Carbon::parse($event->event_start_date)->format('d M Y');
        $finalEventEndDate = Carbon::parse($event->event_end_date)->format('d M Y');

        if ($event->event_logo_media_id) {
            $eventLogo = Media::where('id', $event->event_logo_media_id)->value('file_url');
        } else {
            $eventLogo = "https://via.placeholder.com/150";
        }

        if ($event->event_logo_inverted_media_id) {
            $eventLogoInverted = Media::where('id', $event->event_logo_inverted_media_id)->value('file_url');
        } else {
            $eventLogoInverted = "https://via.placeholder.com/150";
        }
        if ($event->app_sponsor_logo_media_id) {
            $appSponsorLogo = Media::where('id', $event->app_sponsor_logo_media_id)->value('file_url');
        } else {
            $appSponsorLogo = "https://via.placeholder.com/150";
        }

        if ($event->event_splash_screen_media_id) {
            $eventSplashScreen = Media::where('id', $event->event_splash_screen_media_id)->value('file_url');
        } else {
            $eventSplashScreen = "http://via.placeholder.com/360x640";
        }


        if ($event->event_banner_media_id) {
            $eventBanner = Media::where('id', $event->event_banner_media_id)->value('file_url');
        } else {
            $eventBanner = "http://via.placeholder.com/640x360";
        }

        if ($event->app_sponsor_banner_media_id) {
            $appSponsorBanner = Media::where('id', $event->app_sponsor_banner_media_id)->value('file_url');
        } else {
            $appSponsorBanner = "http://via.placeholder.com/640x360";
        }

        return view('admin.event.details.details', [
            "pageTitle" => "Event details",
            "eventName" => $event->full_name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "eventData" => [
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "eventDetails" => [
                    'full_name' => $event->full_name,
                    'short_name' => $event->short_name,
                    'category' => $event->category,
                    'location' => $event->location,
                    'edition' => $event->edition,

                    'event_full_link' => $event->event_full_link,
                    'event_short_link' => $event->event_short_link,

                    'event_start_date' => $event->event_start_date,
                    'event_end_date' => $event->event_end_date,

                    'finalEventStartDate' => $finalEventStartDate,
                    'finalEventEndDate' => $finalEventEndDate,

                    'is_visible_in_the_app' => $event->is_visible_in_the_app,
                    'is_accessible_in_the_app' => $event->is_accessible_in_the_app,

                    'year' => $event->year,
                    'is_active' => $event->is_active,
                ],
                "eventColors" => [
                    'primary_bg_color' => $event->primary_bg_color,
                    'secondary_bg_color' => $event->secondary_bg_color,
                    'primary_text_color' => $event->primary_text_color,
                    'secondary_text_color' => $event->secondary_text_color,
                ],
                "eventHTMLTexts" => [
                    'description_html_text' => $event->description_html_text,
                    'login_html_text' => $event->login_html_text,
                    'continue_as_guest_html_text' => $event->continue_as_guest_html_text,
                    'forgot_password_html_text' => $event->forgot_password_html_text,
                ],
                "eventWebViewLinks" => [
                    'delegate_feedback_survey_link' => $event->delegate_feedback_survey_link,
                    'app_feedback_survey_link' => $event->app_feedback_survey_link,
                    'about_event_link' => $event->about_event_link,
                    'venue_link' => $event->venue_link,
                    'press_releases_link' => $event->press_releases_link,
                ],
                "eventFloorPlanLinks" => [
                    'floor_plan_3d_image_link' => $event->floor_plan_3d_image_link,
                    'floor_plan_exhibition_image_link' => $event->floor_plan_exhibition_image_link,
                ],
                "eventAssets" => [
                    'event_logo' => [
                        'media_id' => $event->event_logo_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_logo_media_id, MediaEntityTypes::EVENT_LOGO->value, $event->id),
                        'url' => $eventLogo,
                    ],
                    'event_logo_inverted' => [
                        'media_id' => $event->event_logo_inverted_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_logo_inverted_media_id, MediaEntityTypes::EVENT_LOGO_INVERTED->value, $event->id),
                        'url' => $eventLogoInverted,
                    ],
                    'app_sponsor_logo' => [
                        'media_id' => $event->app_sponsor_logo_media_id,
                        'media_usage_id' => getMediaUsageId($event->app_sponsor_logo_media_id, MediaEntityTypes::EVENT_APP_SPONSOR_LOGO->value, $event->id),
                        'url' => $appSponsorLogo
                    ],

                    'event_splash_screen' => [
                        'media_id' => $event->event_splash_screen_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_splash_screen_media_id, MediaEntityTypes::EVENT_SPLASH_SCREEN->value, $event->id),
                        'url' => $eventSplashScreen,
                    ],
                    'event_banner' => [
                        'media_id' => $event->event_banner_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_banner_media_id, MediaEntityTypes::EVENT_BANNER->value, $event->id),
                        'url' => $eventBanner,
                    ],
                    'app_sponsor_banner' => [
                        'media_id' => $event->app_sponsor_banner_media_id,
                        'media_usage_id' => getMediaUsageId($event->app_sponsor_banner_media_id, MediaEntityTypes::EVENT_APP_SPONSOR_BANNER->value, $event->id),
                        'url' => $appSponsorBanner,
                    ],
                ],
            ],
        ]);
    }







    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventsList()
    {
        $events = Event::get();

        $data = array();
        if ($events->isNotEmpty()) {
            foreach ($events as $event) {
                array_push($data, [
                    'id' => $event->id,
                    'category' => $event->category,
                    'full_name' => $event->full_name,
                    'short_name' => $event->short_name,
                    'edition' => $event->edition,
                    'location' => $event->location,
                    'description_html_text' => $event->description_html_text,
                    'event_full_link' => $event->event_full_link,
                    'event_short_link' => $event->event_short_link,
                    'event_start_date' => $event->event_start_date,
                    'event_end_date' => $event->event_end_date,

                    'event_logo' => Media::where('id', $event->event_logo_media_id)->value('file_url'),
                    'event_logo_inverted' => Media::where('id', $event->event_logo_inverted_media_id)->value('file_url'),
                    'app_sponsor_logo' => Media::where('id', $event->app_sponsor_logo_media_id)->value('file_url'),

                    'event_splash_screen' => Media::where('id', $event->event_splash_screen_media_id)->value('file_url'),
                    'event_banner' => Media::where('id', $event->event_banner_media_id)->value('file_url'),
                    'app_sponsor_banner' => Media::where('id', $event->app_sponsor_banner_media_id)->value('file_url'),

                    'login_html_text' => $event->login_html_text,
                    'forgot_password_html_text' => $event->forgot_password_html_text,

                    'primary_bg_color' => $event->primary_bg_color,
                    'secondary_bg_color' => $event->secondary_bg_color,
                    'primary_text_color' => $event->primary_text_color,
                    'secondary_text_color' => $event->secondary_text_color,

                    'is_visible_in_the_app' => $event->is_visible_in_the_app,
                    'is_accessible_in_the_app' => $event->is_accessible_in_the_app,
                ]);
            }

            return $this->success($data, "Events List", 200);
        } else {
            return $this->success(null, "There's no events yet.", 200);
        }
    }

    public function apiEventHomepage($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $attendee = Attendee::where('id', $attendeeId)->where('event_id', $eventId)->where('is_active', true)->first();

        $data = [
            'event_logo_inverted' => Media::where('id', $event->event_logo_inverted_media_id)->value('file_url'),
            'event_banner' => Media::where('id', $event->event_banner_media_id)->value('file_url'),
            'attendee_details' => [
                'pfp' => Media::where('id', $attendee->pfp_media_id)->value('file_url'),
                'salutation' => $attendee->salutation,
                'first_name' => $attendee->first_name,
                'middle_name' => $attendee->middle_name,
                'last_name' => $attendee->last_name,
                'email_address' => $attendee->email_address,
            ],
            'speakers' => $this->apiGetSpeakersList($eventCategory, $eventId),
            'programs' => $this->apiGetProgramsList($eventCategory, $eventId),
            'sponsors' => $this->apiGetSponsorsList($eventCategory, $eventId),
            'exhibitors' => $this->apiGetExhibitorsList($eventCategory, $eventId),
            'media_partners' => $this->apiGetMrpsList($eventCategory, $eventId),
            'meeting_room_partners' => $this->apiGetMpsList($eventCategory, $eventId),

            'webview' => [
                'delegate_feedback_survey_link' => $event->delegate_feedback_survey_link,
                'app_feedback_survey_link' => $event->app_feedback_survey_link,
                'about_event_link' => $event->about_event_link,
                'venue_link' => $event->venue_link,
                'press_releases_link' => $event->press_releases_link,
            ],

            'floor_plan' => [
                'floor_plan_3d_image_link' => $event->floor_plan_3d_image_link,
                'floor_plan_exhibition_image_link' => $event->floor_plan_exhibition_image_link,
            ],

            'notifications' => null,
        ];
        return $this->success($data, "Event Homepage details", 200);
    }




    public function apiGetSpeakersList($eventCategory, $eventId){
        $speakers = Speaker::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($speakers->isEmpty()) {
            return null;
        } else {
            $data = array();
            $categorizedSpeakers = array();

            foreach ($speakers as $speaker) {
                if ($speaker->feature_id == 0) {
                    $speakerTypeName = SpeakerType::where('id', $speaker->speaker_type_id)->where('event_id', $eventId)->value('name');
                    array_push($categorizedSpeakers, [
                        'id' => $speaker->id,
                        'salutation' => $speaker->salutation,
                        'first_name' => $speaker->first_name,
                        'middle_name' => $speaker->middle_name,
                        'last_name' => $speaker->last_name,
                        'company_name' => $speaker->company_name,
                        'job_title' => $speaker->job_title,
                        'speaker_type_name' => $speakerTypeName,
                        'pfp' => Media::where('id', $speaker->pfp_media_id)->value('file_url'),
                    ]);
                }
            }

            if (count($categorizedSpeakers) > 0) {
                array_push($data, [
                    'speakerCategoryName' => "Main Conference",
                    'speakerCategoryTextColor' => $event->primary_text_color,
                    'speakerCategoryBackgroundColor' => $event->primary_bg_color,
                    'speakers' => $categorizedSpeakers,
                ]);
            }

            if ($features->isNotEmpty()) {
                foreach ($features as $feature) {
                    $categorizedSpeakers = array();
                    foreach ($speakers as $speaker) {
                        if ($speaker->feature_id == $feature->id) {
                            $speakerTypeName = SpeakerType::where('id', $speaker->speaker_type_id)->where('event_id', $eventId)->value('name');
                            array_push($categorizedSpeakers, [
                                'id' => $speaker->id,
                                'salutation' => $speaker->salutation,
                                'first_name' => $speaker->first_name,
                                'middle_name' => $speaker->middle_name,
                                'last_name' => $speaker->last_name,
                                'company_name' => $speaker->company_name,
                                'job_title' => $speaker->job_title,
                                'speaker_type_name' => $speakerTypeName,
                                'pfp' => Media::where('id', $speaker->pfp_media_id)->value('file_url'),
                            ]);
                        }
                    }

                    if (count($categorizedSpeakers) > 0) {
                        array_push($data, [
                            'speakerCategoryName' => $feature->short_name,
                            'speakerCategoryTextColor' => $feature->primary_text_color,
                            'speakerCategoryBackgroundColor' => $feature->primary_bg_color,
                            'speakers' => $categorizedSpeakers,
                        ]);
                    }
                }
            }
            return $data;
        }
    }

    public function apiGetProgramsList($eventCategory, $eventId){
        $sessions = Session::where('event_id', $eventId)->where('is_active', true)->orderBy('session_date', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($sessions->isEmpty()) {
            return null;
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

                            $getSponsorLogoUrl = null;
                            if ($session->sponsor_id) {
                                $getSponsorLogoId = Sponsor::where('id', $session->sponsor_id)->value('logo_media_id');
                                $getSponsorLogoUrl = Media::where('id', $getSponsorLogoId)->value('file_url');
                            }

                            array_push($sessionsTemp, [
                                'session_id' => $session->id,
                                'start_time' => $session->start_time,
                                'end_time' => $session->end_time,
                                'title' => $session->title,
                                'speakers_mini_headshot' => $getSpeakersHeadshot,
                                'sponsor_mini_logo' => $getSponsorLogoUrl,
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

                                    $getSponsorLogoUrl = null;
                                    if ($session->sponsor_id) {
                                        $getSponsorLogoId = Sponsor::where('id', $session->sponsor_id)->value('logo_media_id');
                                        $getSponsorLogoUrl = Media::where('id', $getSponsorLogoId)->value('file_url');
                                    }

                                    array_push($sessionsTemp, [
                                        'session_id' => $session->id,
                                        'start_time' => $session->start_time,
                                        'end_time' => $session->end_time,
                                        'title' => $session->title,
                                        'speakers_mini_headshot' => $getSpeakersHeadshot,
                                        'sponsor_mini_logo' => $getSponsorLogoUrl,
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

                    if (count($categorizedSessionsByDate) > 0) {
                        array_push($data, [
                            'program_name' => $feature->short_name,
                            'program_banner' => Media::where('id', $feature->banner_media_id)->value('file_url'),
                            'program_date' => $formattedDate,
                            'session_dates' => $categorizedSessionsByDate,
                        ]);
                    }
                }
            }
            return $data;
        }
    }

    public function apiGetSponsorsList($eventCategory, $eventId)
    {
        $sponsors = Sponsor::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $sponsorTypes = SponsorType::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($sponsors->isEmpty()) {
            return null;
        } else {
            $data = array();

            foreach($sponsorTypes as $sponsorType){
                $categorizedSponsors = array();
                
                foreach($sponsors as $sponsor){
                    if($sponsorType->id == $sponsor->sponsor_type_id){
                        array_push($categorizedSponsors, [
                            'id' => $sponsor->id,
                            'name' => $sponsor->name,
                            'website' => $sponsor->website,
                            'logo' => Media::where('id', $sponsor->logo_media_id)->value('file_url'),
                        ]);
                    }
                }
                if(count($categorizedSponsors) > 0){
                    array_push($data, [
                        'sponsorTypeName' => $sponsorType->name,
                        'sponsorTypeTextColor' => $sponsorType->text_color,
                        'sponsorTypeBackgroundColor' => $sponsorType->background_color,
                        'sponsors' => $categorizedSponsors,
                    ]);
                }
            }
            return $data;
        }
    }

    public function apiGetExhibitorsList($eventCategory, $eventId)
    {
        $exhibitors = Exhibitor::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($exhibitors->isEmpty()) {
            return null;
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
            return $data;
        }
    }

    public function apiGetMrpsList($eventCategory, $eventId)
    {
        $meetingRoomPartners = MeetingRoomPartner::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($meetingRoomPartners->isEmpty()) {
            return null;
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
            return $data;
        }
    }

    public function apiGetMpsList($eventCategory, $eventId)
    {
        $mediaPartners = MediaPartner::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($mediaPartners->isEmpty()) {
            return null;
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
            return $data;
        }
    }
}
