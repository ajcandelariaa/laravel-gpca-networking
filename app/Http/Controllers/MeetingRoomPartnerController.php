<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeetingRoomPartnerController extends Controller
{
    public function eventMeetingRoomPartnerView($eventCategory, $eventId){
        return view('admin.event.meeting-room-partners.meeting_room_partners', [
            "pageTitle" => "Meeting room partners",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
