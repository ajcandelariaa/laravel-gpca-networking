<?php

namespace App\Http\Controllers;

use App\Models\Event;

class FloorPlanController extends Controller
{
    public function eventFloorPlanView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');
    
        return view('admin.event.floor-plan.floor_plan', [
            "pageTitle" => "Floor plan",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
