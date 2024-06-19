<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Models\Attendee;
use App\Models\AttendeePasswordReset;
use App\Models\Event;
use App\Models\Media;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AttendeesController extends Controller
{
    use HttpResponses;

    public function eventAttendeesView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.attendees.attendees_list', [
            "pageTitle" => "Attendees",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventAttendeeView($eventCategory, $eventId, $attendeeId)
    {
        $event = Event::where('id', $eventId)->where('category', $eventCategory)->first();
        $attendee = Attendee::where('id', $attendeeId)->first();

        $passwordResetDetails = array();
        $attendeeResets = AttendeePasswordReset::where('attendee_id', $attendee->id)->get();

        if ($attendeeResets->isNotEmpty()) {
            foreach ($attendeeResets as $attendeeReset) {
                array_push($passwordResetDetails, Carbon::parse($attendeeReset->password_changed_date_time)->format('M j, Y g:i A'));
            }
        }

        $attendeeData = [
            "attendeeId" => $attendee->id,

            "badge_number" => $attendee->badge_number,
            "registration_type" => $attendee->registration_type,

            "pass_type" => $attendee->pass_type,
            "company_name" => $attendee->company_name,
            "company_country" => $attendee->company_country,
            "company_phone_number" => $attendee->company_phone_number,

            "username" => $attendee->username,
            "password" => $attendee->password,

            "salutation" => $attendee->salutation,
            "first_name" => $attendee->first_name,
            "middle_name" => $attendee->middle_name,
            "last_name" => $attendee->last_name,
            "job_title" => $attendee->job_title,

            "email_address" => $attendee->email_address,
            "mobile_number" => $attendee->mobile_number,

            "pfp" => [
                'media_id' => $attendee->pfp_media_id,
                'media_usage_id' => getMediaUsageId($attendee->pfp_media_id, MediaEntityTypes::ATTENDEE_PFP->value, $event->id),
                'url' => Media::where('id', $attendee->pfp_media_id)->value('file_url'),
            ],
            "biography" => $attendee->biography,

            "gender" => $attendee->gender,
            "birthdate" => $attendee->birthdate,
            "country" => $attendee->country,
            "city" => $attendee->city,
            "address" => $attendee->address,
            "nationality" => $attendee->nationality,

            "interests" => $attendee->interests,

            "website" => $attendee->website,
            "facebook" => $attendee->facebook,
            "linkedin" => $attendee->linkedin,
            "twitter" => $attendee->twitter,
            "instagram" => $attendee->instagram,

            "is_active" => $attendee->is_active,
            "joined_date_time" => Carbon::parse($attendee->joined_date_time)->format('M j, Y g:i A'),

            "attendeePasswordResetDetails" => $passwordResetDetails,
        ];

        return view('admin.event.attendees.attendee', [
            "pageTitle" => "Attendee",
            "eventName" => $event->full_name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "attendeeData" => $attendeeData,
        ]);
    }



    public function apiAttendeeLogin(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $attendee = Attendee::where('email_address', $request->username)->orWhere('username', $request->username)->first();

        if ($attendee == null) {
            return $this->error(null, "User not found", 404);
        }

        if (!Hash::check($request->password, $attendee->password)) {
            return $this->error(null, "Invalid credentials", 401);
        }

        $token = $attendee->createToken('api token of ' . $attendee->id)->plainTextToken;
        return $this->success(['token' => $token, 'attendeeId' => $attendee->id], "Logged in successfully", 200);
    }

    public function apiAttendeeLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, "Logged out successfully", 200);
    }
}
