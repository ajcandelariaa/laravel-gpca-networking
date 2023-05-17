<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
    public function mainDashboardView(){
        return view('admin.home.dashboard', [
            "pageTitle" => "Dashboard"
        ]);
    }

    public function eventsView(){
        return view('admin.home.events', [
            "pageTitle" => "Events"
        ]);
    }

    public function eventDashboardView($eventCategory, $eventId){
        return view('admin.event.dashboard.dashboard', [
            "pageTitle" => "Dashboard",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
    
    public function eventDetailsView($eventCategory, $eventId){
        return view('admin.event.details.details', [
            "pageTitle" => "Event details",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
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
