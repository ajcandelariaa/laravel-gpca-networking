<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feature;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SessionController extends Controller
{
    public function eventSessionsView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.sessions.sessions', [
            "pageTitle" => "Session",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSessionView($eventCategory, $eventId, $sessionId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $session = Session::where('id', $sessionId)->first();

        if ($session) {
            if($session->feature_id == 0){
                $category = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $session->feature_id)->first();
                if($feature){
                    $category = $feature->short_name;
                } else {
                    $category = "Others";
                }
            }

            $sessionData = [
                "sessionId" => $session->id,
                "sessionCategoryName" => $category,
                "sessionFeatureId" => $session->feature_id,

                "sessionDate" => $session->session_date,
                "sessionDateName" => Carbon::parse($session->session_date)->format('F d, Y'),

                "sessionDay" => $session->session_day,
                "sessionType" => $session->session_type,
                "sessionTitle" => $session->title,
                "sessionDescription" => $session->description,
                "sessionStartTime" => $session->start_time,
                "sessionEndTime" => $session->end_time,
                "sessionLocation" => $session->location,

                "sessionStatus" => $session->active,
                "sessionDateTimeAdded" => Carbon::parse($session->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.sessions.session', [
                "pageTitle" => "Session",
                "eventName" => $event->name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "sessionData" => $sessionData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }
}
