<?php

namespace App\Http\Controllers;

use App\Enums\MediaEntityTypes;
use App\Enums\PassTypes;
use App\Models\Attendee;
use App\Models\AttendeeFavoriteExhibitor;
use App\Models\AttendeeFavoriteMp;
use App\Models\AttendeeFavoriteMrp;
use App\Models\AttendeeFavoriteSession;
use App\Models\AttendeeFavoriteSpeaker;
use App\Models\AttendeeFavoriteSponsor;
use App\Models\AttendeePasswordReset;
use App\Models\Event;
use App\Models\Exhibitor;
use App\Models\Media;
use App\Models\MediaPartner;
use App\Models\MeetingRoomPartner;
use App\Models\Session;
use App\Models\Speaker;
use App\Models\Sponsor;
use App\Models\SponsorType;
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






    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiAttendeeLogin(Request $request, $apiCode, $eventCategory, $eventId)
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

        // Create token
        $tokenResult = $attendee->createToken('api token of ' . $attendee->id);

        $token = $tokenResult->accessToken;
        $expiresAt = now()->addDay();
        $token->expires_at = $expiresAt;
        $token->save();

        return $this->success(['token' => $tokenResult->plainTextToken, 'expires_at' => $expiresAt, 'attendeeId' => $attendee->id], "Logged in successfully", 200);
    }

    public function apiAttendeeLogout(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $request->user()->currentAccessToken()->delete();
        return $this->success(null, "Logged out successfully", 200);
    }

    public function apiAttendeeProfile($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $attendee = Attendee::where('id', $attendeeId)->where('event_id', $eventId)->first();

        if ($attendee->pass_type == PassTypes::FULL_MEMBER->value) {
            $passTypeName = "Full Member";
        } else if ($attendee->pass_type == PassTypes::MEMBER->value) {
            $passTypeName = "Member";
        } else {
            $passTypeName = "Non-Member";
        }

        return $this->success([
            'attendee_id' => $attendee->id,
            'badge_number' => $attendee->badge_number,
            'registration_type' => $attendee->registration_type,
            'pass_type' => $passTypeName,
            'company_name' => $attendee->company_name,
            'company_country' => $attendee->company_country,
            'company_phone_number' => $attendee->company_phone_number,
            'username' => $attendee->username,
            'salutation' => $attendee->salutation,
            'first_name' => $attendee->first_name,
            'middle_name' => $attendee->middle_name,
            'last_name' => $attendee->last_name,
            'job_title' => $attendee->job_title,
            'email_address' => $attendee->email_address,
            'mobile_number' => $attendee->mobile_number,
            'pfp' => Media::where('id', $attendee->pfp_media_id)->value('file_url'),
            'biography' => $attendee->biography,
            'gender' => $attendee->gender,
            'birthdate' => Carbon::parse($attendee->birthdate)->format('F d, Y'),
            'country' => $attendee->country,
            'city' => $attendee->city,
            'address' => $attendee->address,
            'nationality' => $attendee->nationality,
            'website' => $attendee->website,
            'facebook' => $attendee->facebook,
            'linkedin' => $attendee->linkedin,
            'twitter' => $attendee->twitter,
            'instagram' => $attendee->instagram,
            'joined_date_time' => Carbon::parse($attendee->joined_date_time)->format('F d, Y'),
        ], "Attendee details", 200);
    }


    public function apiAttendeeEditProfileDetails(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $request->validate([
            'attendee_id' => 'required',

            'salutation' => 'nullable',
            'first_name' => 'required',
            'middle_name' => 'nullable',
            'last_name' => 'required',
            'job_title' => 'required',

            'biography' => 'nullable',

            'mobile_number' => 'nullable',

            'gender' => 'nullable',
            'birthdate' => 'nullable|date',
            'country' => 'nullable',
            'city' => 'nullable',
            'address' => 'nullable',
            'nationality' => 'nullable',

            'website' => 'nullable',
            'facebook' => 'nullable',
            'linkedin' => 'nullable',
            'twitter' => 'nullable',
            'instagram' => 'nullable',
        ]);

        try {
            Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                'salutation' => $request->salutation,
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,

                'job_title' => $request->job_title,

                'biography' => $request->biography,

                'mobile_number' => $request->mobile_number,

                'gender' => $request->gender,
                'birthdate' => $request->birthdate,
                'country' => $request->country,
                'city' => $request->city,
                'address' => $request->address,
                'nationality' => $request->nationality,

                'website' => $request->website,
                'facebook' => $request->facebook,
                'linkedin' => $request->linkedin,
                'twitter' => $request->twitter,
                'instagram' => $request->instagram,
            ]);
            return $this->success(['attendee_id' => $request->attendee_id], "Attendee details updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error(null, "An error occurred while updating attendee details", 500);
        }
    }

    public function apiAttendeeEditProfileUsernameEmail(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId){
        $request->validate([
            'attendee_id' => 'required',
            'email_address' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);

        $attendeePassword = Attendee::where('id', $request->attendee_id)->value('password');
        if (Hash::check($request->password, $attendeePassword)) {
            if (checkAttendeeEmailIfExistsInDatabase($request->attendee_id, $eventId, $request->email_address)) {
                return $this->success(null, "Email is already registered, please use another email!", 200);
            }

            if (checkAttendeeUsernameIfExistsInDatabase($request->attendee_id, $eventId, $request->username)) {
                return $this->success(null, "Username is already registered, please use another username!", 200);
            }

            Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                'username' => $request->username,
                'email_address' => $request->email_address,
            ]);

            return $this->success(['attendee_id' => $request->attendee_id], "Attendee Username/Email address updated successfully", 200);
        } else {
            return $this->success(null, "Incorrect attendee password", 403);
        }
    }

    public function apiAttendeeEditProfilePassword(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId){
        $request->validate([
            'attendee_id' => 'required',
            'old_password' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required',
        ]);

        if($request->password == $request->old_password){
            return $this->success(null, "Password is just the same, please enter a different one", 200);
        }

        if(!Hash::check($request->old_password, Attendee::where('id',  $request->attendee_id)->where('event_id', $eventId)->value('password'))){
            return $this->success(null, "Old password doesn't match", 403);
        }

        if($request->password != $request->confirm_password){
            return $this->success(null, "Password & Confirm password does not match", 200);
        }

        Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
            'password' => Hash::make($request->password),
        ]);
        
        AttendeePasswordReset::create([
            'event_id' => $eventId,
            'attendee_id' => $request->attendee_id,
            'password_changed_date_time' => Carbon::now(),
        ]);

        return $this->success(null, "Password updated successfuly", 200);
    }


    public function apiAttendeeFavorites($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $favoriteSessions = array();
        $favoriteSpeakers = array();
        $favoriteSponsors = array();
        $favoriteExhibitors = array();
        $favoriteMrps = array();
        $favoriteMps = array();

        $attendeeFavoriteSessions = AttendeeFavoriteSession::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
        $attendeeFavoriteSpeakers = AttendeeFavoriteSpeaker::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
        $attendeeFavoriteSponsors = AttendeeFavoriteSponsor::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
        $attendeeFavoriteExhibitors = AttendeeFavoriteExhibitor::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
        $attendeeFavoriteMrps = AttendeeFavoriteMrp::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
        $attendeeFavoriteMps = AttendeeFavoriteMp::where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();

        if ($attendeeFavoriteSessions->isNotEmpty()) {
            foreach ($attendeeFavoriteSessions as $attendeeFavoriteSession) {
                $session = Session::where('id', $attendeeFavoriteSession->session_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($session) {
                    array_push($favoriteSessions, [
                        'session_id' => $session->id,
                        'title' => $session->title,
                        'start_time' => $session->start_time,
                        'end_time' => $session->end_time,
                        'session_date' => Carbon::parse($session->session_date)->format('F d, Y'),
                        'session_week_day' => Carbon::parse($session->session_date)->format('l'),
                        'session_day' => $session->session_day,
                    ]);
                }
            }
        }


        if ($attendeeFavoriteSpeakers->isNotEmpty()) {
            foreach ($attendeeFavoriteSpeakers as $attendeeFavoriteSpeaker) {
                $speaker = Speaker::where('id', $attendeeFavoriteSpeaker->speaker_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($speaker) {
                    array_push($favoriteSpeakers, [
                        'speaker_id' => $speaker->id,
                        'salutation' => $speaker->salutation,
                        'first_name' => $speaker->first_name,
                        'middle_name' => $speaker->middle_name,
                        'last_name' => $speaker->last_name,
                        'company_name' => $speaker->company_name,
                        'job_title' => $speaker->job_title,
                        'pfp' => Media::where('id', $speaker->pfp_media_id)->value('file_url'),
                    ]);
                }
            }
        }

        if ($attendeeFavoriteSponsors->isNotEmpty()) {
            foreach ($attendeeFavoriteSponsors as $attendeeFavoriteSponsor) {
                $sponsor = Sponsor::where('id', $attendeeFavoriteSponsor->sponsor_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($sponsor) {
                    array_push($favoriteSponsors, [
                        'sponsor_id' => $sponsor->id,
                        'name' => $sponsor->name,
                        'type' => SponsorType::where('id', $sponsor->sponsor_type_id)->where('event_id', $eventId)->value('name'),
                        'logo' => Media::where('id', $sponsor->logo_media_id)->value('file_url'),
                    ]);
                }
            }
        }

        if ($attendeeFavoriteExhibitors->isNotEmpty()) {
            foreach ($attendeeFavoriteExhibitors as $attendeeFavoriteExhibitor) {
                $exhibitor = Exhibitor::where('id', $attendeeFavoriteExhibitor->exhibitor_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($exhibitor) {
                    array_push($favoriteExhibitors, [
                        'exhibitor_id' => $exhibitor->id,
                        'name' => $exhibitor->name,
                        'stand_number' => $exhibitor->stand_number,
                        'logo' => Media::where('id', $exhibitor->logo_media_id)->value('file_url'),
                    ]);
                }
            }
        }

        if ($attendeeFavoriteMrps->isNotEmpty()) {
            foreach ($attendeeFavoriteMrps as $attendeeFavoriteMrp) {
                $meetingRoomPartner = MeetingRoomPartner::where('id', $attendeeFavoriteMrp->meeting_room_partner_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($meetingRoomPartner) {
                    array_push($favoriteMrps, [
                        'meetingRoomPartner_id' => $meetingRoomPartner->id,
                        'name' => $meetingRoomPartner->name,
                        'location' => $meetingRoomPartner->location,
                        'logo' => Media::where('id', $meetingRoomPartner->logo_media_id)->value('file_url'),
                    ]);
                }
            }
        }

        if ($attendeeFavoriteMps->isNotEmpty()) {
            foreach ($attendeeFavoriteMps as $attendeeFavoriteMp) {
                $mediaPartner = MediaPartner::where('id', $attendeeFavoriteMp->media_partner_id)->where('event_id', $eventId)->where('is_active', true)->first();

                if ($mediaPartner) {
                    array_push($favoriteMps, [
                        'mediaPartner_id' => $mediaPartner->id,
                        'name' => $mediaPartner->name,
                        'website' => $mediaPartner->website,
                        'logo' => Media::where('id', $mediaPartner->logo_media_id)->value('file_url'),
                    ]);
                }
            }
        }

        $data = [
            'sessions' => $favoriteSessions,
            'speakers' => $favoriteSpeakers,
            'sponsors' => $favoriteSponsors,
            'exhibitors' => $favoriteExhibitors,
            'meeting_room_partners' => $favoriteMrps,
            'media_partners' => $favoriteMps,
        ];


        return $this->success($data, "Favorite details", 200);
    }
}
