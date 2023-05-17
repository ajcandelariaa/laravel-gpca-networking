<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendeesController extends Controller
{
    public function eventAttendeesView($eventCategory, $eventId){
        return view('admin.event.attendees.attendees', [
            "pageTitle" => "Attendees",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
