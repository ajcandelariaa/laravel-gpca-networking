<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Feature;
use App\Models\Speaker;
use App\Models\SpeakerType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    public function eventSpeakersView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');

        return view('admin.event.speakers.speakers_list', [
            "pageTitle" => "Speakers",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSpeakerTypesView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');

        return view('admin.event.speakers.speaker_types', [
            "pageTitle" => "Speakers Type",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSpeakerView($eventCategory, $eventId, $speakerId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $speaker = Speaker::where('id', $speakerId)->first();

        if ($speaker) {
            if ($speaker->pfp) {
                $speakerPFP = Storage::url($speaker->pfp);
                $speakerPFPDefault = false;
            } else {
                $speakerPFP = asset('assets/images/pfp-placeholder.jpg');
                $speakerPFPDefault = true;
            }

            if ($speaker->cover_photo) {
                $speakerCoverPhoto = Storage::url($speaker->cover_photo);
                $speakerCoverPhotoDefault = false;
            } else {
                $speakerCoverPhoto = asset('assets/images/cover-photo-placeholder.jpg');
                $speakerCoverPhotoDefault = true;
            }

            if ($speaker->feature_id == 0) {
                $category = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $speaker->feature_id)->first();
                if ($feature) {
                    $category = $feature->short_name;
                } else {
                    $category = "Others";
                }
            }

            $speakerType = SpeakerType::where('event_id', $event->id)->where('id', $speaker->speaker_type_id)->first();
            if ($speakerType) {
                $type = $speakerType->name;
            } else {
                $type = "N/A";
            }

            $speakerData = [
                "speakerId" => $speaker->id,
                "speakerCategoryName" => $category,
                "speakerFeatureId" => $speaker->feature_id,
                "speakerTypeName" => $type,
                "speakerTypeId" => $speaker->speaker_type_id,
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

                "speakerCountry" => $speaker->country,
                "speakerEmailAddress" => $speaker->email_address,
                "speakerMobileNumber" => $speaker->mobile_number,
                "speakerWebsite" => $speaker->website,
                "speakerFacebook" => $speaker->facebook,
                "speakerLinkedin" => $speaker->linkedin,
                "speakerTwitter" => $speaker->twitter,
                "speakerInstagram" => $speaker->instagram,

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
        } else {
            abort(404, 'Data not found');
        }
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





    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiSpeakersList($eventCategory, $eventId)
    {
        $speakers = Speaker::where('event_id', $eventId)->where('active', true)->get();

        if ($speakers->isNotEmpty()) {
            $data = array();

            foreach($speakers as $speaker){
                array_push($data, [
                    'id' => $speaker->id,
                    'feature_id' => $speaker->feature_id,
                    'speaker_type_id' => $speaker->speaker_type_id,

                    'salutation' => $speaker->salutation,
                    'first_name' => $speaker->first_name,
                    'middle_name' => $speaker->middle_name,
                    'last_name' => $speaker->last_name,

                    'company_name' => $speaker->company_name,
                    'job_title' => $speaker->job_title,

                    'biography' => $speaker->biography,
                    'pfp' => $speaker->pfp,
                    'cover_photo' => $speaker->cover_photo,

                    'country' => $speaker->country,
                    'email_address' => $speaker->email_address,
                    'mobile_number' => $speaker->mobile_number,
                    'website' => $speaker->website,
                    'facebook' => $speaker->facebook,
                    'linkedin' => $speaker->linkedin,
                    'twitter' => $speaker->twitter,
                    'instagram' => $speaker->instagram,
                ]);
            }
        } else {
            return response()->json([
                'status' => 200,
                'message' => "There's no speaker yet.",
            ], 200);
        }
    }
}
