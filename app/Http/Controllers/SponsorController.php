<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteSponsor;
use App\Models\Event;
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
        $sponsor = Sponsor::with(['event', 'feature', 'sponsorType', 'logo', 'banner'])->where('id', $sponsorId)->first();

        if ($sponsor) {
            if ($sponsor->feature_id == 0) {
                $categoryName = $sponsor->event->short_name;
            } else {
                if ($sponsor->feature) {
                    $categoryName = $sponsor->feature->short_name;
                } else {
                    $categoryName = "Others";
                }
            }

            if ($sponsor->sponsorType) {
                $typeName = $sponsor->sponsorType->name;
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
                    'url' => $sponsor->logo->file_url ?? null,
                ],
                "banner" => [
                    'media_id' => $sponsor->banner_media_id,
                    'media_usage_id' => getMediaUsageId($sponsor->banner_media_id, MediaEntityTypes::SPONSOR_BANNER->value, $sponsor->id),
                    'url' => $sponsor->banner->file_url ?? null,
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
                "eventName" => $sponsor->event->full_name,
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
    public function apiEventSponsors($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $sponsors = Sponsor::with('logo')->where('event_id', $eventId)->where('is_active', true)->orderBy('datetime_added', 'ASC')->get();
            $sponsorTypes = SponsorType::where('event_id', $eventId)->orderBy('datetime_added', 'ASC')->get();

            if ($sponsors->isEmpty()) {
                return $this->error(null, "No sponsors available at the moment.", 404);
            }

            $data = array();

            foreach ($sponsorTypes as $sponsorType) {
                $categorizedSponsors = array();
                foreach ($sponsors as $sponsor) {
                    if ($sponsorType->id == $sponsor->sponsor_type_id) {
                        array_push($categorizedSponsors, [
                            'id' => $sponsor->id,
                            'name' => $sponsor->name,
                            'website' => $sponsor->website,
                            'logo' => $sponsor->logo->file_url ?? null,
                        ]);
                    }
                }

                if (count($categorizedSponsors) > 0) {
                    array_push($data, [
                        'sponsorTypeName' => $sponsorType->name,
                        'sponsorTypeTextColor' => $sponsorType->text_color,
                        'sponsorTypeBackgroundColor' => $sponsorType->background_color,
                        'sponsors' => $categorizedSponsors,
                    ]);
                }
            }
            return $this->success($data, "Sponsors list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the sponsor list", 500);
        }
    }

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
