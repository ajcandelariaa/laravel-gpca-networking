<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MediaPartnerController extends Controller
{
    public function eventMediaPartnerView($eventCategory, $eventId){
        return view('admin.event.media-partners.media_partners', [
            "pageTitle" => "Media partners",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function getListOfMediaPartners() {
        return response()->json();
    }
}
