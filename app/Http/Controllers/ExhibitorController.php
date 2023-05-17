<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExhibitorController extends Controller
{
    public function eventExhibitorsView($eventCategory, $eventId){
        return view('admin.event.exhibitors.exhibitors', [
            "pageTitle" => "Exhibitors",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
