<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\AttendeeFavoriteMp;
use App\Models\Event;
use App\Models\MediaPartner;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MediaPartnerController extends Controller
{
    use HttpResponses;

    public function eventMediaPartnersView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.media-partners.media_partners', [
            "pageTitle" => "Media partners",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventMediaPartnerView($eventCategory, $eventId, $mediaPartnerId)
    {
        $mediaPartner = MediaPartner::with(['event', 'logo', 'banner'])->where('id', $mediaPartnerId)->first();

        if ($mediaPartner) {
            $mediaPartnerData = [
                "mediaPartnerId" => $mediaPartner->id,

                "name" => $mediaPartner->name,
                "profile_html_text" => $mediaPartner->profile_html_text,

                "logo" => [
                    'media_id' => $mediaPartner->logo_media_id,
                    'media_usage_id' => getMediaUsageId($mediaPartner->logo_media_id, MediaEntityTypes::MEDIA_PARTNER_LOGO->value, $mediaPartner->id),
                    'url' => $mediaPartner->logo->file_url ?? null,
                ],
                "banner" => [
                    'media_id' => $mediaPartner->banner_media_id,
                    'media_usage_id' => getMediaUsageId($mediaPartner->banner_media_id, MediaEntityTypes::MEDIA_PARTNER_BANNER->value, $mediaPartner->id),
                    'url' => $mediaPartner->banner->file_url ?? null,
                ],

                "country" => $mediaPartner->country,
                "contact_person_name" => $mediaPartner->contact_person_name,
                "email_address" => $mediaPartner->email_address,
                "mobile_number" => $mediaPartner->mobile_number,
                "website" => $mediaPartner->website,
                "facebook" => $mediaPartner->facebook,
                "linkedin" => $mediaPartner->linkedin,
                "twitter" => $mediaPartner->twitter,
                "instagram" => $mediaPartner->instagram,

                "is_active" => $mediaPartner->is_active,
                "datetime_added" => Carbon::parse($mediaPartner->datetime_added)->format('M j, Y g:i A'),
            ];

            return view('admin.event.media-partners.media_partner', [
                "pageTitle" => "Media Partner",
                "eventName" => $mediaPartner->event->full_name,
                "eventCategory" => $eventCategory,
                "eventId" => $eventId,
                "mediaPartnerData" => $mediaPartnerData,
            ]);
        } else {
            abort(404, 'Data not found');
        }
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventMediaPartnerDetail($apiCode, $eventCategory, $eventId, $attendeeId, $mediaPartnerId)
    {
        try {
            $mediaPartner = MediaPartner::with('logo')->where('id', $mediaPartnerId)->where('event_id', $eventId)->where('is_active', true)->first();

            if (!$mediaPartner) {
                return $this->error(null, "Media Partner doesn't exist", 404);
            }

            $data = [
                'media_partner_id' => $mediaPartner->id,
                'logo' => $mediaPartner->logo->file_url ?? null,
                'name' => $mediaPartner->name,
                'profile_html_text' => $mediaPartner->profile_html_text,
                'country' => $mediaPartner->country,
                'contact_person_name' => $mediaPartner->contact_person_name,
                'email_address' => $mediaPartner->email_address,
                'mobile_number' => $mediaPartner->mobile_number,
                'website' => $mediaPartner->website,
                'facebook' => $mediaPartner->facebook,
                'linkedin' => $mediaPartner->linkedin,
                'twitter' => $mediaPartner->twitter,
                'instagram' => $mediaPartner->instagram,
                'is_favorite' => AttendeeFavoriteMp::where('event_id', $eventId)->where('attendee_id', $attendeeId)->where('media_partner_id', $mediaPartnerId)->exists(),
                'favorite_count' => AttendeeFavoriteMp::where('event_id', $eventId)->where('media_partner_id', $mediaPartnerId)->count(),
            ];
            return $this->success($data, "Media Partner details", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the meeting room partner details", 500);
        }
    }


    public function apiEventMediaPartnerMarkAsFavorite(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendeeId' => 'required|exists:attendees,id',
            'mediaPartnerId' => 'required|exists:media_partners,id',
            'isFavorite' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $favorite = AttendeeFavoriteMp::where('event_id', $eventId)->where('attendee_id', $request->attendeeId)->where('media_partner_id', $request->mediaPartnerId)->first();

            if ($request->isFavorite) {
                if (!$favorite) {
                    AttendeeFavoriteMp::create([
                        'event_id' => $eventId,
                        'attendee_id' => $request->attendeeId,
                        'media_partner_id' => $request->mediaPartnerId,
                    ]);
                }
            } else {
                if ($favorite) {
                    $favorite->delete();
                }
            }
            return $this->success(null, "Media Partner favorite status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the favorite status", 500);
        }
    }
}
