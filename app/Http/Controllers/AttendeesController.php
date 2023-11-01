<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use App\Models\AttendeePasswordReset;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class AttendeesController extends Controller
{
    public function eventAttendeesView($eventCategory, $eventId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');

        return view('admin.event.attendees.attendees_list', [
            "pageTitle" => "Attendees",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }

    public function eventAttendeeView($eventCategory, $eventId, $attendeeId){
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('name');
        $attendee = Attendee::where('id', $attendeeId)->first();

        if($attendee->pfp){
            $attendeePFPDefault = false;
            $attendeePFP = Storage::url($attendee->pfp);
        } else {
            $attendeePFPDefault = true;
            $attendeePFP = asset('assets/images/pfp-placeholder.jpg');
        }

        $passwordResetDetails = array();
        $attendeeResets = AttendeePasswordReset::where('attendee_id', $attendee->id)->get();

        if($attendeeResets->isNotEmpty()){
            foreach($attendeeResets as $attendeeReset){
                array_push($passwordResetDetails, Carbon::parse($attendeeReset->password_changed_date_time)->format('M j, Y g:i A'));
            }
        }

        $attendeeData = [
            "attendeeId" => $attendee->id,
            "attendeeSalutation" => $attendee->salutation,
            "attendeeFirstName" => $attendee->first_name,
            "attendeeMiddleName" => $attendee->middle_name,
            "attendeeLastName" => $attendee->last_name,
            "attendeeJobTitle" => $attendee->job_title,
            "attendeeCompany" => $attendee->company_name,
            "attendeeUsername" => $attendee->username,
            "attendeePassword" => $attendee->password,
            "attendeeEmail" => $attendee->email_address,
            "attendeeMobileNumber" => $attendee->mobile_number,
            "attendeeLandlineNumber" => $attendee->landline_number,
            "attendeeCountry" => $attendee->country,
            "attendeeBadgeNumber" => $attendee->badge_number,
            "attendeePassType" => $attendee->pass_type,
            "attendeeRegistrationType" => $attendee->registration_type,
            "attendeeBiography" => $attendee->biography,
            "attendeePFP" => $attendeePFP,
            "attendeePFPDefault" => $attendeePFPDefault,
            "attendeeAddedDateTime" => Carbon::parse($attendee->joined_date_time)->format('M j, Y g:i A'),
            "attendeePasswordResetDetais" => $passwordResetDetails,
            "attendeeWebsite" => $attendee->website,
            "attendeeFacebook" => $attendee->facebook,
            "attendeeLinkedin" => $attendee->linkedin,
            "attendeeTwitter" => $attendee->twitter,
            "attendeeInstagram" => $attendee->instagram,
        ];

        return view('admin.event.attendees.attendee', [
            "pageTitle" => "Attendee",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "attendeeData" => $attendeeData,
        ]);
    }
}
