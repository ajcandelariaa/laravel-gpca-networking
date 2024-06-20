<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Attendee;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Media;
use App\Models\MediaPartner;
use App\Models\MeetingRoomPartner;
use App\Models\Session;
use App\Models\Speaker;
use App\Models\Sponsor;
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

        if($event){
            $attendee = Attendee::where('id', $attendeeId)->where('event_id', $eventId)->where('is_active', true)->first();

            $data = [
                'event_logo_inverted' => Media::where('id', $event->event_logo_inverted_media_id)->value('file_url'),
                'event_banner' => Media::where('id', $event->event_banner_media_id)->value('file_url'),
                'speaker_count' => Speaker::where('event_id', $eventId)->where('is_active', true)->count(),
                'session_count' => Session::where('event_id', $eventId)->where('is_active', true)->count(),
                'sponsor_count' => Sponsor::where('event_id', $eventId)->where('is_active', true)->count(),
                'exhibitor_count' => Exhibitor::where('event_id', $eventId)->where('is_active', true)->count(),
                'media_partner_count' => MediaPartner::where('event_id', $eventId)->where('is_active', true)->count(),
                'meeting_room_partner_count' => MeetingRoomPartner::where('event_id', $eventId)->where('is_active', true)->count(),
                'attendee_details' => [
                    'pfp' => Media::where('id', $attendee->pfp_media_id)->value('file_url'),
                    'salutation' => $attendee->salutation,
                    'first_name' => $attendee->first_name,
                    'middle_name' => $attendee->middle_name,
                    'last_name' => $attendee->last_name,
                    'email_address' => $attendee->email_address,
                ],
                'notification_count' => 0,
            ];
            return $this->success($data, "Event Homepage details", 200);
        } else {
            return $this->success(null, "Event doesn't exist.", 200);
        }
    }
}
