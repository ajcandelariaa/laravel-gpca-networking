<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function eventSpeakersView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        
        return view('admin.event.speakers.speakers_list', [
            "pageTitle" => "Speakers",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSpeakerView($eventCategory, $eventId, $speakerId){
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $speaker = Speaker::where('id', $speakerId)->first();

        if($speaker->pfp){
            $speakerPFP = Storage::url($speaker->pfp);
            $speakerPFPDefault = false;
        } else {
            $speakerPFP = asset('assets/images/attendee-image-placeholder.jpg');
            $speakerPFPDefault = true;
        }

        if($speaker->cover_photo){
            $speakerCoverPhoto = Storage::url($speaker->cover_photo);
            $speakerCoverPhotoDefault = false;
        } else {
            $speakerCoverPhoto = asset('assets/images/attendee-cover-photo-placeholder.jpg');
            $speakerCoverPhotoDefault = true;
        }
        
        $speakerData = [
            "speakerId" => $speaker->id,
            "speakerSalutation" => $speaker->salutation,
            "speakerFirstName" => $speaker->first_name,
            "speakerMiddleName" => $speaker->middle_name,
            "speakerLastName" => $speaker->last_name,
            "speakerCompanyName" => $speaker->company_name,
            "speakerJobTitle" => $speaker->job_title,
            "speakerBiography" => $speaker->biography,
            "speakerPFP" => $speakerPFP,
            "speakerPFPDefault" => $speakerPFPDefault,
            "speakerCoverPhoto" => $speakerCoverPhoto,
            "speakerCoverPhotoDefault" => $speakerCoverPhotoDefault,
            "speakerStatus" => $speaker->active,
            "speakerDateTimeAdded" => Carbon::parse($speaker->datetime_added)->format('M j, Y g:i A'),
        ];
        return view('admin.event.speakers.speaker', [
            "pageTitle" => "Speakers",
            "eventName" => $event->name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "speakerData" => $speakerData,
        ]);
    }
    
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
