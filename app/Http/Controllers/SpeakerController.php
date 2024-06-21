<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Speaker;
use App\Models\SpeakerType;
use App\Traits\HttpResponses;
use Carbon\Carbon;

class SpeakerController extends Controller
{
    use HttpResponses;

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
    public function apiEventSpeakers($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $speakers = Speaker::where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
        $features = Feature::where('event_id', $eventId)->where('is_active', true)->get();
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();

        if ($speakers->isEmpty()) {
            return $this->success(null, "There are no speaker yet", 200);
        } else {
            $data = array();
            $categorizedSpeakers = array();

            foreach ($speakers as $speaker) {
                if ($speaker->feature_id == 0) {
                    $speakerTypeName = SpeakerType::where('id', $speaker->speaker_type_id)->where('event_id', $eventId)->value('name');
                    array_push($categorizedSpeakers, [
                        'id' => $speaker->id,
                        'salutation' => $speaker->salutation,
                        'first_name' => $speaker->first_name,
                        'middle_name' => $speaker->middle_name,
                        'last_name' => $speaker->last_name,
                        'company_name' => $speaker->company_name,
                        'job_title' => $speaker->job_title,
                        'speaker_type_name' => $speakerTypeName,
                        'logo' => Media::where('id', $speaker->logo_media_id)->value('file_url'),
                    ]);
                }
            }

            if (count($categorizedSpeakers) > 0) {
                array_push($data, [
                    'speakerCategoryName' => "Main Conference",
                    'speakerCategoryTextColor' => $event->primary_text_color,
                    'speakerCategoryBackgroundColor' => $event->primary_bg_color,
                    'speakers' => $categorizedSpeakers,
                ]);
            }

            if ($features->isNotEmpty()) {
                foreach($features as $feature){
                    $categorizedSpeakers = array();
                    foreach ($speakers as $speaker) {
                        if ($speaker->feature_id == $feature->id) {
                            $speakerTypeName = SpeakerType::where('id', $speaker->speaker_type_id)->where('event_id', $eventId)->value('name');
                            array_push($categorizedSpeakers, [
                                'id' => $speaker->id,
                                'salutation' => $speaker->salutation,
                                'first_name' => $speaker->first_name,
                                'middle_name' => $speaker->middle_name,
                                'last_name' => $speaker->last_name,
                                'company_name' => $speaker->company_name,
                                'job_title' => $speaker->job_title,
                                'speaker_type_name' => $speakerTypeName,
                                'logo' => Media::where('id', $speaker->logo_media_id)->value('file_url'),
                            ]);
                        }
                    }
    
                    if (count($categorizedSpeakers) > 0) {
                        array_push($data, [
                            'speakerCategoryName' => $feature->short_name,
                            'speakerCategoryTextColor' => $feature->primary_text_color,
                            'speakerCategoryBackgroundColor' => $feature->primary_bg_color,
                            'speakers' => $categorizedSpeakers,
                        ]);
                    }
                }
            }
            return $this->success($data, "Speakers list", 200);
        }
    }
}
