<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FloorPlanController extends Controller
{
    public function eventFloorPlanView($eventCategory, $eventId){
        return view('admin.event.floor-plan.floor_plan', [
            "pageTitle" => "Floor plan",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
