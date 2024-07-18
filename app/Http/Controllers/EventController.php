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
use App\Models\Sponsor;
use App\Models\SponsorType;
use App\Traits\HttpResponses;
use Carbon\Carbon;

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
        $finalEvents = [];
        $events = Event::with('eventLogo')->orderBy('event_start_date', 'desc')->get();

        if ($events->isNotEmpty()) {
            foreach ($events as $event) {
                $eventFormattedDate =  Carbon::parse($event->event_start_date)->format('d M Y') . ' - ' . Carbon::parse($event->event_end_date)->format('d M Y');
                array_push($finalEvents, [
                    'eventId' => $event->id,
                    'eventLogo' => $event->eventLogo->file_url,
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
        $event = Event::with(['eventLogo', 'eventLogoInverted', 'appSponsorLogo', 'eventSplashScreen', 'eventBanner', 'appSponsorBanner'])->where('id', $eventId)->first();

        $finalEventStartDate = Carbon::parse($event->event_start_date)->format('d M Y');
        $finalEventEndDate = Carbon::parse($event->event_end_date)->format('d M Y');

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
                        'url' => $event->eventLogo->file_url ?? null,
                    ],
                    'event_logo_inverted' => [
                        'media_id' => $event->event_logo_inverted_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_logo_inverted_media_id, MediaEntityTypes::EVENT_LOGO_INVERTED->value, $event->id),
                        'url' => $event->eventLogoInverted->file_url ?? null,
                    ],
                    'app_sponsor_logo' => [
                        'media_id' => $event->app_sponsor_logo_media_id,
                        'media_usage_id' => getMediaUsageId($event->app_sponsor_logo_media_id, MediaEntityTypes::EVENT_APP_SPONSOR_LOGO->value, $event->id),
                        'url' => $event->appSponsorLogo->file_url ?? null,
                    ],

                    'event_splash_screen' => [
                        'media_id' => $event->event_splash_screen_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_splash_screen_media_id, MediaEntityTypes::EVENT_SPLASH_SCREEN->value, $event->id),
                        'url' => $event->eventSplashScreen->file_url ?? null,
                    ],
                    'event_banner' => [
                        'media_id' => $event->event_banner_media_id,
                        'media_usage_id' => getMediaUsageId($event->event_banner_media_id, MediaEntityTypes::EVENT_BANNER->value, $event->id),
                        'url' => $event->eventBanner->file_url ?? null,
                    ],
                    'app_sponsor_banner' => [
                        'media_id' => $event->app_sponsor_banner_media_id,
                        'media_usage_id' => getMediaUsageId($event->app_sponsor_banner_media_id, MediaEntityTypes::EVENT_APP_SPONSOR_BANNER->value, $event->id),
                        'url' => $event->appSponsorBanner->file_url ?? null,
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
        try {
            $events = Event::with(['eventLogo', 'eventSplashScreen'])->where('is_visible_in_the_app', true)->get();

            if ($events->isEmpty()) {
                return $this->error(null, "There's no events yet.", 404);
            }

            $data = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'category' => $event->category,

                    'short_name' => $event->short_name,
                    'edition' => $event->edition,
                    'location' => $event->location,
                    'event_date' => Carbon::parse($event->event_start_date)->format('d') . '-' . Carbon::parse($event->event_end_date)->format('d F Y'),

                    'event_logo' => $event->eventLogo->file_url ?? null,
                    'event_splash_screen' => $event->eventSplashScreen->file_url ?? null,

                    'login_html_text' => $event->login_html_text,
                    'forgot_password_html_text' => $event->forgot_password_html_text,

                    'primary_bg_color' => $event->primary_bg_color,
                    'secondary_bg_color' => $event->secondary_bg_color,
                    'primary_text_color' => $event->primary_text_color,
                    'secondary_text_color' => $event->secondary_text_color,

                    'is_accessible_in_the_app' => $event->is_accessible_in_the_app,
                ];
            });
            return $this->success($data, "Events List", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the list of events", 500);
        }
    }

    public function apiEventHomepage($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $event = Event::with(['eventLogoInverted', 'eventBanner'])->where('id', $eventId)->where('category', $eventCategory)->first();
            $attendee = Attendee::with('pfp')->where('id', $attendeeId)->where('event_id', $eventId)->first();

            $data = [
                'event_logo_inverted' => $event->eventLogoInverted->file_url ?? null,
                'event_banner' => $event->eventBanner->file_url ?? null,
                'attendee_details' => [
                    'pfp' => $attendee->pfp->file_url ?? null,
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
                'meeting_room_partners' => $this->apiGetMrpsList($eventCategory, $eventId),
                'media_partners' => $this->apiGetMpsList($eventCategory, $eventId),

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
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the event details", 500);
        }
    }




    public function apiGetSpeakersList($eventCategory, $eventId)
    {
        $speakers = Speaker::with(['pfp', 'speakerType'])->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($speakers->isEmpty()) {
            return null;
        }

        $data = [];
        $mainConferenceSpeakers = $speakers->filter(function ($speaker) {
            return $speaker->feature_id == 0;
        })->map(function ($speaker) {
            return [
                'id' => $speaker->id,
                'salutation' => $speaker->salutation,
                'first_name' => $speaker->first_name,
                'middle_name' => $speaker->middle_name,
                'last_name' => $speaker->last_name,
                'company_name' => $speaker->company_name,
                'job_title' => $speaker->job_title,
                'speaker_type_name' => $speaker->speakerType->name,
                'pfp' => $speaker->pfp->file_url ?? null,
            ];
        });

        if ($mainConferenceSpeakers->isNotEmpty()) {
            $data[] = [
                'speakerCategoryName' => "Main Conference",
                'speakerCategoryTextColor' => $event->primary_text_color,
                'speakerCategoryBackgroundColor' => $event->primary_bg_color,
                'speakers' => $mainConferenceSpeakers,
            ];
        }

        foreach ($features as $feature) {
            $categorizedSpeakers = $speakers->filter(function ($speaker) use ($feature) {
                return $speaker->feature_id == $feature->id;
            })->map(function ($speaker) {
                return [
                    'id' => $speaker->id,
                    'salutation' => $speaker->salutation,
                    'first_name' => $speaker->first_name,
                    'middle_name' => $speaker->middle_name,
                    'last_name' => $speaker->last_name,
                    'company_name' => $speaker->company_name,
                    'job_title' => $speaker->job_title,
                    'speaker_type_name' => $speaker->speakerType->name,
                    'pfp' => $speaker->pfp->file_url ?? null,
                ];
            });

            if ($categorizedSpeakers->isNotEmpty()) {
                $data[] = [
                    'speakerCategoryName' => $feature->short_name,
                    'speakerCategoryTextColor' => $feature->primary_text_color,
                    'speakerCategoryBackgroundColor' => $feature->primary_bg_color,
                    'speakers' => $categorizedSpeakers,
                ];
            }
        }
        return $data;
    }

    public function apiGetProgramsList($eventCategory, $eventId)
    {
        $sessions = Session::with(['feature', 'sponsor.logo'])->where('event_id', $eventId)->where('is_active', true)->orderBy('session_date', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($sessions->isEmpty()) {
            return null;
        }

        $data = [];
        $categorizedSessionsByDate = [];
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
            $sessionsTemp = [];
            foreach ($sessions as $session) {
                if ($session->feature_id == 0) {
                    if ($session->session_date == $uniqueDate) {
                        $getSpeakersHeadshot = [];

                        $sessionSpeakersTemp = SessionSpeaker::where('event_id', $eventId)->where('session_id', $session->id)->get();

                        if ($sessionSpeakersTemp->isNotEmpty()) {
                            foreach ($sessionSpeakersTemp as $sessionSpeakerTemp) {
                                $speaker = Speaker::with('pfp')->where('event_id', $eventId)->where('id', $sessionSpeakerTemp->speaker_id)->first();
                                $getSpeakersHeadshot[] = $speaker->pfp->file_url ?? null;
                            }
                        }

                        array_push($sessionsTemp, [
                            'session_id' => $session->id,
                            'start_time' => $session->start_time,
                            'end_time' => $session->end_time,
                            'title' => $session->title,
                            'speakers_mini_headshot' => $getSpeakersHeadshot,
                            'sponsor_mini_logo' => $session->sponsor->logo->file_url ?? null,
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
                $categorizedSessionsByDate = [];
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
                    $sessionsTemp = [];
                    foreach ($sessions as $session) {
                        if ($session->feature_id == $feature->id) {
                            if ($session->session_date == $uniqueDate) {
                                $getSpeakersHeadshot = [];

                                $sessionSpeakersTemp = SessionSpeaker::where('event_id', $eventId)->where('session_id', $session->id)->get();

                                if ($sessionSpeakersTemp->isNotEmpty()) {
                                    foreach ($sessionSpeakersTemp as $sessionSpeakerTemp) {
                                        $speaker = Speaker::with('pfp')->where('event_id', $eventId)->where('id', $sessionSpeakerTemp->speaker_id)->first();
                                        $getSpeakersHeadshot[] = $speaker->pfp->file_url ?? null;
                                    }
                                }

                                array_push($sessionsTemp, [
                                    'session_id' => $session->id,
                                    'start_time' => $session->start_time,
                                    'end_time' => $session->end_time,
                                    'title' => $session->title,
                                    'speakers_mini_headshot' => $getSpeakersHeadshot,
                                    'sponsor_mini_logo' => $session->sponsor->logo->file_url ?? null,
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

    public function apiGetSponsorsList($eventCategory, $eventId)
    {
        $sponsors = Sponsor::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $sponsorTypes = SponsorType::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

        if ($sponsors->isEmpty()) {
            return null;
        }

        $data = [];

        foreach ($sponsorTypes as $sponsorType) {
            $categorizedSponsors = $sponsors->filter(function ($sponsor) use ($sponsorType) {
                return $sponsor->sponsor_type_id == $sponsorType->id;
            })->map(function ($sponsor) {
                return [
                    'id' => $sponsor->id,
                    'name' => $sponsor->name,
                    'website' => $sponsor->website,
                    'logo' => $sponsor->logo->file_url ?? null,
                ];
            });

            if ($categorizedSponsors->isNotEmpty()) {
                $data[] = [
                    'sponsorTypeName' => $sponsorType->name,
                    'sponsorTypeTextColor' => $sponsorType->text_color,
                    'sponsorTypeBackgroundColor' => $sponsorType->background_color,
                    'sponsors' => $categorizedSponsors,
                ];
            }
        }

        return $data;
    }

    public function apiGetExhibitorsList($eventCategory, $eventId)
    {
        $exhibitors = Exhibitor::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($exhibitors->isEmpty()) {
            return null;
        }

        return $exhibitors->map(function ($exhibitor) {
            return [
                'id' => $exhibitor->id,
                'name' => $exhibitor->name,
                'stand_number' => $exhibitor->stand_number,
                'logo' => $exhibitor->logo->file_url ?? null,
            ];
        });
    }

    public function apiGetMrpsList($eventCategory, $eventId)
    {
        $meetingRoomPartners = MeetingRoomPartner::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($meetingRoomPartners->isEmpty()) {
            return null;
        }

        return $meetingRoomPartners->map(function ($meetingRoomPartner) {
            return [
                'id' => $meetingRoomPartner->id,
                'name' => $meetingRoomPartner->name,
                'location' => $meetingRoomPartner->location,
                'logo' => $meetingRoomPartner->logo->file_url ?? null,
            ];
        });
    }

    public function apiGetMpsList($eventCategory, $eventId)
    {
        $mediaPartners = MediaPartner::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();

        if ($mediaPartners->isEmpty()) {
            return null;
        }

        return $mediaPartners->map(function ($mediaPartner) {
            return [
                'id' => $mediaPartner->id,
                'name' => $mediaPartner->name,
                'website' => $mediaPartner->website,
                'logo' => $mediaPartner->logo->file_url ?? null,
            ];
        });
    }
}
