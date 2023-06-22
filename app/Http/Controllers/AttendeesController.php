<?php

namespace App\Http\Controllers;

use App\Models\Event;

class AttendeesController extends Controller
{
    public function eventAttendeesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');

        return view('admin.event.attendees.attendees', [
            "pageTitle" => "Attendees",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
