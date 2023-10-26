<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class VenueController extends Controller
{
    public function eventVenueView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.venue.venue', [
            "pageTitle" => "Venue",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
