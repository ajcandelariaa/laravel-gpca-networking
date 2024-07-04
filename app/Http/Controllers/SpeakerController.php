<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteSpeaker;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Session;
use App\Models\SessionSpeaker;
use App\Models\Speaker;
use App\Models\SpeakerType;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
    public function apiEventSpeakerDetail($apiCode, $eventCategory, $eventId, $attendeeId, $speakerId)
    {
        try {
            $speaker = Speaker::with(['pfp', 'feature', 'speakerType'])->where('id', $speakerId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$speaker) {
                return $this->error(null, "Speaker doesn't exist", 404);
            }
            
            $speakerSessions = [];
            $sessionSpeakers = SessionSpeaker::where('event_id', $eventId)->where('speaker_id', $speakerId)->get();

            if ($sessionSpeakers->isNotEmpty()) {
                $speakerSessions = $sessionSpeakers->map(function ($sessionSpeaker) use ($eventId) {
                    $session = Session::where('id', $sessionSpeaker->session_id)->where('event_id', $eventId)->where('is_active', true)->first();
                    return [
                        'session_id' => $session->id,
                        'title' => $session->title,
                        'start_time' => $session->start_time,
                        'end_time' => $session->end_time,
                        'session_date' => Carbon::parse($session->session_date)->format('F d, Y'),
                        'session_week_day' => Carbon::parse($session->session_date)->format('l'),
                        'session_day' => $session->session_day,
                    ];
                });
            }

            $data = [
                'speaker_id' => $speaker->id,
                'salutation' => $speaker->salutation,
                'first_name' => $speaker->first_name,
                'middle_name' => $speaker->middle_name,
                'last_name' => $speaker->last_name,

                'company_name' => $speaker->company_name,
                'job_title' => $speaker->job_title,

                'biography_html_text' => $speaker->biography_html_text,

                'speakerCategoryName' => $speaker->feature->short_name ?? "Main Conference",
                'speakerTypeName' => $speaker->speakerType->name ?? "Speaker",

                'pfp' => $speaker->pfp->file_url ?? null,

                'website' => $speaker->website,
                'facebook' => $speaker->facebook,
                'linkedin' => $speaker->linkedin,
                'twitter' => $speaker->twitter,
                'instagram' => $speaker->instagram,

                'is_favorite' => AttendeeFavoriteSpeaker::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('speaker_id', $speakerId)->exists(),
                'favorite_count' => AttendeeFavoriteSpeaker::where('event_id', $eventId)->where('speaker_id', $speakerId)->count(),

                'sessions' => $speakerSessions,
            ];
            return $this->success($data, "Speaker details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the speaker details", 500);
        }
    }



    public function apiEventSpeakerMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'speakerId' => 'required|exists:speakers,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteSpeaker::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('speaker_id', $request->speakerId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteSpeaker::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'speaker_id' => $request->speakerId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }
            return $this->success(null, "Speaker favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }
}
