<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Speaker;
use App\Models\SpeakerType;
use Carbon\Carbon;

class SpeakerController extends Controller
{
    public function eventSpeakersView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.speakers.speakers_list', [
            "pageTitle" => "Speakers",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSpeakerTypesView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

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
            if ($speaker->pfp_media_id) {
                $speakerPFPUrl = Media::where('id', $speaker->pfp_media_id)->value('file_url');
            } else {
                $speakerPFPUrl = asset('assets/images/pfp-placeholder.jpg');
            }

            if ($speaker->cover_photo_media_id) {
                $speakerCoverPhotoUrl = Media::where('id', $speaker->cover_photo_media_id)->value('file_url');
            } else {
                $speakerCoverPhotoUrl = asset('assets/images/cover-photo-placeholder.jpg');
            }

            if ($speaker->feature_id == 0) {
                $categoryName = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $speaker->feature_id)->first();
                if ($feature) {
                    $categoryName = $feature->short_name;
                } else {
                    $categoryName = "Others";
                }
            }

            $speakerType = SpeakerType::where('event_id', $event->id)->where('id', $speaker->speaker_type_id)->first();
            if ($speakerType) {
                $typeName = $speakerType->name;
            } else {
                $typeName = "N/A";
            }

            $speakerData = [
                "id" => $speaker->id,

                "categoryName" => $categoryName,
                "feature_id" => $speaker->feature_id,
                "typeName" => $typeName,
                "speaker_type_id" => $speaker->speaker_type_id,

                "salutation" => $speaker->salutation,
                "first_name" => $speaker->first_name,
                "middle_name" => $speaker->middle_name,
                "last_name" => $speaker->last_name,
                "company_name" => $speaker->company_name,
                "job_title" => $speaker->job_title,
                "biography_html_text" => $speaker->biography_html_text,
                'pfp' => [
                    'media_id' => $speaker->pfp_media_id,
                    'media_usage_id' => getMediaUsageId($speaker->pfp_media_id, MediaEntityTypes::SPEAKER_PFP->value, $speaker->id),
                    'url' => $speakerPFPUrl,
                ],
                'cover_photo' => [
                    'media_id' => $speaker->cover_photo_media_id,
                    'media_usage_id' => getMediaUsageId($speaker->cover_photo_media_id, MediaEntityTypes::SPEAKER_COVER_PHOTO->value, $speaker->id),
                    'url' => $speakerCoverPhotoUrl,
                ],
                "country" => $speaker->country,
                "email_address" => $speaker->email_address,
                "mobile_number" => $speaker->mobile_number,
                "website" => $speaker->website,
                "facebook" => $speaker->facebook,
                "linkedin" => $speaker->linkedin,
                "twitter" => $speaker->twitter,
                "instagram" => $speaker->instagram,

                "is_active" => $speaker->is_active,
                "datetime_added" => Carbon::parse($speaker->datetime_added)->format('M j, Y g:i A'),
            ];
            return view('admin.event.speakers.speaker', [
                "pageTitle" => "Speakers",
                "eventName" => $event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "speakerData" => $speakerData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }


    
    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiSpeakersList($eventId)
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
