<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SpeakerController extends Controller
{
    
    public function getListOfEvents()
    {
        return response()->json(array(
            [
                'speakerId' => '1',
                'speakerName' => 'Supply Chain Conference',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 1',
            ],
            [
                'speakerId' => '2',
                'speakerName' => 'Plastics Conference',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 2',
            ],
            [
                'speakerId' => '3',
                'speakerName' => 'Agri-Nutrients Conference',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 3',
            ],
            [
                'speakerId' => '4',
                'speakerName' => 'Research & Innovation Conference',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 4',
            ],
            [
                'speakerId' => '5',
                'speakerName' => 'Responsible Care',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 5',
            ],
            [
                'speakerId' => '6',
                'speakerName' => 'GPCA Annual Forum',
                'speakerImage' => "17-19 May, 2022",
                'speakerBio' => 'Place 6',
            ],
        ));
    }
}
