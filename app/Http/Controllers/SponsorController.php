<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class SponsorController extends Controller
{
    public function eventSponsorsView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.sponsors.sponsors', [
            "pageTitle" => "Sponsors",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function getListOfSponsors() {
        return response()->json();
    }
}
