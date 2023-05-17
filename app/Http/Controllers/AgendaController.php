<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AgendaController extends Controller
{
    public function eventAgendaView($eventCategory, $eventId){
        return view('admin.event.agenda.agenda', [
            "pageTitle" => "Agenda",
            "eventName" => "14th GPCA Supply Chain Conference",
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }
}
