<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Icon;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
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
        $events = Event::all();

        $events = Event::orderBy('event_start_date', 'desc')->get();
        $finalEvents = array();

        if ($events->isNotEmpty()) {
            foreach ($events as $event) {
                $eventFormattedDate =  Carbon::parse($event->event_start_date)->format('d M Y') . ' - ' . Carbon::parse($event->event_end_date)->format('d M Y');

                array_push($finalEvents, [
                    'eventId' => $event->id,
                    'eventLogo' => $event->event_logo,
                    'eventName' => $event->name,
                    'eventCategory' => $event->category,
                    'eventDate' => $eventFormattedDate,
                    'eventLocation' => $event->location,
                    'eventDescription' => $event->description,
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
            "eventCategories" => config('app.eventCategories'),
        ]);
    }

    public function eventDashboardView($eventCategory, $eventId)
    {
        $event = Event::where('id', $eventId)->first();

        return view('admin.event.dashboard.dashboard', [
            "pageTitle" => "Dashboard",
            "eventName" => $event->name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventDetailsView($eventCategory, $eventId)
    {
        $event = Event::where('id', $eventId)->first();

        $finalEventStartDate = Carbon::parse($event->event_start_date)->format('d M Y');
        $finalEventEndDate = Carbon::parse($event->event_end_date)->format('d M Y');

        if($event->event_logo){
            $eventLogo = Storage::url($event->event_logo);
        } else {
            $eventLogo = "https://via.placeholder.com/150";
        }

        if($event->event_logo_inverted){
            $eventLogoInverted = Storage::url($event->event_logo_inverted);
        } else {
            $eventLogoInverted = "https://via.placeholder.com/150";
        }
        
        if($event->app_sponsor_logo){
            $appSponsorLogo = Storage::url($event->app_sponsor_logo);
        } else {
            $appSponsorLogo = "https://via.placeholder.com/150";
        }
        
        if($event->event_splash_screen){
            $eventSplashScreen = Storage::url($event->event_splash_screen);
        } else {
            $eventSplashScreen = "http://via.placeholder.com/360x640";
        }

        
        if($event->event_banner){
            $eventBanner = Storage::url($event->event_banner);
        } else {
            $eventBanner = "http://via.placeholder.com/640x360";
        }

        if($event->app_sponsor_banner){
            $appSponsorBanner = Storage::url($event->app_sponsor_banner);
        } else {
            $appSponsorBanner = "http://via.placeholder.com/640x360";
        }
        
        return view('admin.event.details.details', [
            "pageTitle" => "Event details",
            "eventName" => $event->name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "eventData" => [
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "eventDetails" => [
                    'name' => $event->name,
                    'category' => $event->category,
                    'location' => $event->location,
                    'description' => $event->description,

                    'event_full_link' => $event->event_full_link,
                    'event_short_link' => $event->event_short_link,

                    'event_start_date' => $event->event_start_date,
                    'event_end_date' => $event->event_end_date,

                    'finalEventStartDate' => $finalEventStartDate,
                    'finalEventEndDate' => $finalEventEndDate,

                    'year' => $event->year,
                    'active' => $event->active,
                ],
                "eventAssets" => [
                    'event_logo' => $eventLogo,
                    'event_logo_inverted' => $eventLogoInverted,
                    'app_sponsor_logo' => $appSponsorLogo,

                    'event_splash_screen' => $eventSplashScreen,
                    'event_banner' => $eventBanner,
                    'app_sponsor_banner' => $appSponsorBanner,
                ],
            ],
        ]);
    }






    // =========================================================
    //                       RENDER LOGICS
    // =========================================================

    public function addEvent(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'name' => 'required',
            'location' => 'required',
            'description' => 'required',
            'event_full_link' => 'required',
            'event_short_link' => 'required',
            'event_start_date' => 'required|date',
            'event_end_date' => 'required|date',

            'event_logo' => 'required|mimes:jpeg,png,jpg,gif',
            'event_logo_inverted' => 'required|mimes:jpeg,png,jpg,gif',
            'app_sponsor_logo' => 'required|mimes:jpeg,png,jpg,gif',
            'event_splash_screen' => 'required|mimes:jpeg,png,jpg,gif',
            'event_banner' => 'required|mimes:jpeg,png,jpg,gif',
            'app_sponsor_banner' => 'required|mimes:jpeg,png,jpg,gif',
        ]);

        $currentYear = strval(Carbon::parse($request->event_start_date)->year);

        $fileName1 = time() . '-' . $request->file('event_logo')->getClientOriginalName();
        $fileName2 = time() . '-' . $request->file('event_logo_inverted')->getClientOriginalName();
        $fileName3 = time() . '-' . $request->file('app_sponsor_logo')->getClientOriginalName();
        $fileName4 = time() . '-' . $request->file('event_splash_screen')->getClientOriginalName();
        $fileName5 = time() . '-' . $request->file('event_banner')->getClientOriginalName();
        $fileName6 = time() . '-' . $request->file('app_sponsor_banner')->getClientOriginalName();

        $eventLogoPath = $request->file('event_logo')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/logo', $fileName1);
        $eventLogoInvertedPath = $request->file('event_logo_inverted')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/logo', $fileName2);
        $appSponsorLogoPath = $request->file('app_sponsor_logo')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/logo', $fileName3);

        $eventSplashScreenPath = $request->file('event_splash_screen')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/splash-screen', $fileName4);
        $eventBannerPath = $request->file('event_banner')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/banner', $fileName5);
        $appSponsorBannerPath = $request->file('app_sponsor_banner')->storeAs('public/' . $currentYear . '/'. $request->category . '/details/banner', $fileName6);

        $newEvent = Event::create([
            'category' => $request->category,
            'name' => $request->name,
            'location' => $request->location,
            'description' => $request->description,
            'event_full_link' => $request->event_full_link,
            'event_short_link' => $request->event_short_link,
            'event_start_date' => $request->event_start_date,
            'event_end_date' => $request->event_end_date,

            'event_logo' => $eventLogoInvertedPath,
            'event_logo_inverted' => $appSponsorLogoPath,
            'app_sponsor_logo' => $eventBannerPath,

            'event_splash_screen' => $eventLogoPath,
            'event_banner' => $eventSplashScreenPath,
            'app_sponsor_banner' => $appSponsorBannerPath,

            'year' => $currentYear,
            'active' => true,
        ]);


        // Icon::create([
        //     'event_id' => $newEvent->id,
        //     'icon' => 'test',
        // ]);

        return redirect()->route('admin.events.view')->with('success', 'Event added successfully.');;
    }

    public function getListOfEvents()
    {
        return response()->json(array(
            [
                'eventId' => '1',
                'eventName' => 'Supply Chain Conference',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 1',
                'eventLogo' => 'url1',
            ],
            [
                'eventId' => '2',
                'eventName' => 'Plastics Conference',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 2',
                'eventLogo' => 'url2',
            ],
            [
                'eventId' => '3',
                'eventName' => 'Agri-Nutrients Conference',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 3',
                'eventLogo' => 'url3',
            ],
            [
                'eventId' => '4',
                'eventName' => 'Research & Innovation Conference',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 4',
                'eventLogo' => 'url4',
            ],
            [
                'eventId' => '5',
                'eventName' => 'Responsible Care',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 5',
                'eventLogo' => 'url5',
            ],
            [
                'eventId' => '6',
                'eventName' => 'GPCA Annual Forum',
                'eventDate' => "17-19 May, 2022",
                'eventVenue' => 'Place 6',
                'eventLogo' => 'url6',
            ],
        ));
    }
}
