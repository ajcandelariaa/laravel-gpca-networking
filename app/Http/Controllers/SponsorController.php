<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteSponsor;
use App\Models\Event;
use App\Models\Feature;
use App\Models\Media;
use App\Models\Sponsor;
use App\Models\SponsorType;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SponsorController extends Controller
{
    use HttpResponses;

    public function eventSponsorsView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.sponsors.sponsors', [
            "pageTitle" => "Sponsors",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventSponsorTypesView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.sponsors.sponsor_types', [
            "pageTitle" => "Sponsors Type",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }



    public function eventSponsorView($eventCategory, $eventId, $sponsorId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $sponsor = Sponsor::where('id', $sponsorId)->first();

        if ($sponsor) {
            if ($sponsor->feature_id == 0) {
                $categoryName = $event->short_name;
            } else {
                $feature = Feature::where('event_id', $event->id)->where('id', $sponsor->feature_id)->first();
                if ($feature) {
                    $categoryName = $feature->short_name;
                } else {
                    $categoryName = "Others";
                }
            }

            $sponsorType = SponsorType::where('event_id', $event->id)->where('id', $sponsor->sponsor_type_id)->first();
            if ($sponsorType) {
                $typeName = $sponsorType->name;
            } else {
                $typeName = "N/A";
            }

            $sponsorData = [
                "sponsorId" => $sponsor->id,

                "categoryName" => $categoryName,
                "feature_id" => $sponsor->feature_id,
                "typeName" => $typeName,
                "sponsor_type_id" => $sponsor->sponsor_type_id,

                "name" => $sponsor->name,
                "profile_html_text" => $sponsor->profile_html_text,

                "logo" => [
                    'media_id' => $sponsor->logo_media_id,
                    'media_usage_id' => getMediaUsageId($sponsor->logo_media_id, MediaEntityTypes::SPONSOR_LOGO->value, $sponsor->id),
                    'url' => Media::where('id', $sponsor->logo_media_id)->value('file_url'),
                ],
                "banner" => [
                    'media_id' => $sponsor->banner_media_id,
                    'media_usage_id' => getMediaUsageId($sponsor->banner_media_id, MediaEntityTypes::SPONSOR_BANNER->value, $sponsor->id),
                    'url' => Media::where('id', $sponsor->banner_media_id)->value('file_url'),
                ],

                "country" => $sponsor->country,
                "contact_person_name" => $sponsor->contact_person_name,
                "email_address" => $sponsor->email_address,
                "mobile_number" => $sponsor->mobile_number,
                "website" => $sponsor->website,
                "facebook" => $sponsor->facebook,
                "linkedin" => $sponsor->linkedin,
                "twitter" => $sponsor->twitter,
                "instagram" => $sponsor->instagram,

                "is_active" => $sponsor->is_active,
                "datetime_added" => Carbon::parse($sponsor->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.sponsors.sponsor', [
                "pageTitle" => "Sponsor",
                "eventName" => $event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "sponsorData" => $sponsorData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventSponsorDetail($apiCode, $eventCategory, $eventId, $attendeeId, $sponsorId)
    {
        try {
            $sponsor = Sponsor::with(['event', 'logo', 'feature', 'sponsorType'])->where('id', $sponsorId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$sponsor) {
                return $this->error(null, "Sponsor doesn't exist", 404);
            }

            if ($sponsor->feature_id == 0) {
                $categoryName = $sponsor->event->short_name ?? null;
            } else {
                $categoryName = $sponsor->feature->short_name ?? null;
            }

            $data = [
                'sponsor_id' => $sponsor->id,
                'logo' => $sponsor->logo->file_url ?? null,
                'name' => $sponsor->name,
                'category' => $categoryName,
                'type' => $sponsor->sponsorType->name ?? null,
                'profile_html_text' => $sponsor->profile_html_text,
                'country' => $sponsor->country,
                'contact_person_name' => $sponsor->contact_person_name,
                'email_address' => $sponsor->email_address,
                'mobile_number' => $sponsor->mobile_number,
                'website' => $sponsor->website,
                'facebook' => $sponsor->facebook,
                'linkedin' => $sponsor->linkedin,
                'twitter' => $sponsor->twitter,
                'instagram' => $sponsor->instagram,
                'is_favorite' => AttendeeFavoriteSponsor::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('sponsor_id', $sponsorId)->exists(),
                'favorite_count' => AttendeeFavoriteSponsor::where('event_id', $eventId)->where('sponsor_id', $sponsorId)->count(),
            ];
            return $this->success($data, "Sponsor details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the sponsor details", 500);
        }
    }


    public function apiEventSponsorMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'sponsorId' => 'required|exists:sponsors,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteSponsor::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('sponsor_id', $request->sponsorId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteSponsor::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'sponsor_id' => $request->sponsorId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }

            return $this->success(null, "Sponsor favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }
}
