<?php

namespace App\Http\Controllers;

use App\Enums\FileUploadDirectory;
use App\Enums\MediaEntityTypes;
use App\Enums\PassTypes;
use App\Enums\PasswordChangedBy;
use App\Mail\AttendeeResetPassword;
use App\Mail\EmailChanged;
use App\Mail\ForgotPasswordOtp;
use App\Mail\UsernameChanged;
use App\Models\Attendee;
use App\Models\AttendeeDeviceToken;
use App\Models\AttendeeFavoriteExhibitor;
use App\Models\AttendeeFavoriteMp;
use App\Models\AttendeeFavoriteMrp;
use App\Models\AttendeeFavoriteSession;
use App\Models\AttendeeFavoriteSpeaker;
use App\Models\AttendeeFavoriteSponsor;
use App\Models\AttendeePasswordReset;
use App\Models\Event;
use App\Models\ForgotPasswordReset;
use App\Models\Media;
use App\Models\SingleConversation;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

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
        $attendee = Attendee::with(['event', 'pfp', 'passwordResets'])->find($attendeeId);

        if (!$attendee) {
            abort(404, 'The URL is incorrect');
        }

        $passwordResetDetails = [];
        if ($attendee->passwordResets->isNotEmpty()) {
            $passwordResetDetails = $attendee->passwordResets->map(function ($attendeeReset) {
                return [
                    'changed_by' => $attendeeReset->password_changed_by,
                    'datetime' => Carbon::parse($attendeeReset->password_changed_date_time)->format('M j, Y g:i A'),
                ];
            })->toArray();
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
                'media_usage_id' => getMediaUsageId($attendee->pfp_media_id, MediaEntityTypes::ATTENDEE_PFP->value, $attendee->id),
                'url' => $attendee->pfp->file_url ?? null,
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
            "eventName" => $attendee->event->full_name,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
            "attendeeData" => $attendeeData,
        ]);
    }


    public function eventAddAttendeeFromApiView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.attendees.add_attendee_from_api', [
            "pageTitle" => "Add Attendee from API",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }





    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiAttendeeLogin(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::where('event_id', $eventId)->where('email_address', $request->username)->orWhere('username', $request->username)->where('is_active', true)->first();
            
            return $this->error($attendee, "Invalid credentials", 401);

            if (!$attendee) {
                return $this->error(null, "Invalid credentials", 401);
            }

            if(!(Hash::check($request->password, $attendee->password))){
                return $this->error(null, "Invalid credentials", 401);
            }

            $attendeeDeviceToken = AttendeeDeviceToken::where('event_id', $eventId)->where('attendee_id', $attendee->id)->where('device_token', $request->device_token)->first();

            if (!$attendeeDeviceToken) {
                AttendeeDeviceToken::create([
                    'event_id' => $eventId,
                    'attendee_id' => $attendee->id,
                    'device_token' => $request->device_token,
                ]);
            }

            $tokenResult = $attendee->createToken('api token of ' . $attendee->id);

            $token = $tokenResult->accessToken;
            $expiresAt = now()->addDay();
            $token->expires_at = $expiresAt;
            $token->save();

            return $this->success(['token' => $tokenResult->plainTextToken, 'expires_at' => $expiresAt, 'attendeeId' => $attendee->id], "Logged in successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while logging in", 500);
        }
    }

    public function apiForgotPasswordSendOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::with('event')->where('email_address', $request->email_address)->first();

            if (!$attendee) {
                return $this->error(null, "Email address doesn't exist", 404);
            }

            $otp = generateOTP();

            ForgotPasswordReset::create([
                'attendee_id' => $attendee->id,
                'email_address' => $request->email_address,
                'otp' => Hash::make($otp),
                'expires_at' => Carbon::now()->addMinutes(30),
            ]);

            $details = [
                'subject' => 'Your password reset OTP for ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,

                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
                'otp' => $otp,
            ];

            Mail::to($request->email_address)->send(new ForgotPasswordOtp($details));

            return $this->success(null, "OTP sent successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while sending the OTP", 500);
        }
    }

    public function apiForgotPasswordVerifyOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $checkOtp = ForgotPasswordReset::where('email_address', $request->email_address)->where('is_used', false)->where('expires_at', '>', Carbon::now())->first();

            if (!$checkOtp || !Hash::check($request->otp, $checkOtp->otp)) {
                return $this->error(null, "Invalid or expired OTP", 400);
            }

            $checkOtp->update(['is_used' => true]);

            return $this->success(['attendee_id' => $checkOtp->attendee_id, 'otp_id' => $checkOtp->id], "OTP verified", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while verifying the otp", 500);
        }
    }

    public function apiForgotPasswordReset(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required|exists:attendees,id',
            'otp_id' => 'required|exists:forgot_password_resets,id',
            'password' => 'required|min:8',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            if ($request->password != $request->confirm_password) {
                return $this->error(null, "Password and Confirm password do not match", 409);
            }

            $resetRecord = ForgotPasswordReset::where('id', $request->otp_id)->where('attendee_id', $request->attendee_id)->where('is_password_changed', false)->first();

            if (!$resetRecord) {
                return $this->error(null, "Invalid OTP or password already changed", 400);
            }

            Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                'password' => Hash::make($request->password),
            ]);

            AttendeePasswordReset::create([
                'event_id' => $eventId,
                'attendee_id' => $request->attendee_id,
                'password_changed_by' => PasswordChangedBy::ATTENDEE->value,
                'password_changed_date_time' => Carbon::now(),
            ]);

            ForgotPasswordReset::where('id', $request->otp_id)->update([
                'is_password_changed' => true,
            ]);

            $attendee = Attendee::with('event')->where('id', $request->attendee_id)->first();

            $details = [
                'subject' => 'Password reset for ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,

                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
            ];

            Mail::to($attendee->email_address)->send(new AttendeeResetPassword($details));

            return $this->success(null, "Password reset successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while resetting password", 500);
        }
    }

    public function apiAttendeeLogout(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'device_token' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $request->user()->currentAccessToken()->delete();

            $attendeeDevice = AttendeeDeviceToken::where('event_id', $eventId)->where('attendee_id', $request->attendee_id)->where('device_token', $request->device_token)->first();

            if ($attendeeDevice) {
                $attendeeDevice->delete();
            }

            return $this->success(null, "Logged out successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while logging out", 500);
        }
    }

    public function apiAttendeeProfile($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $attendee = Attendee::with('pfp')->where('id', $attendeeId)->where('event_id', $eventId)->first();

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
                'pfp' => $attendee->pfp->file_url ?? null,
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
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the attendee details", 500);
        }
    }


    public function apiAttendeeEditProfileDetails(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
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

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

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
            return $this->success(null, "Attendee details updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating attendee details", 500);
        }
    }

    public function apiAttendeeEditProfileUsernameEmail(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'email_address' => 'required|email',
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::with('event')->where('id', $request->attendee_id)->first();
            if (Hash::check($request->password, $attendee->password)) {
                if ($attendee->email_address != $request->email_address) {
                    if (checkAttendeeEmailIfExistsInDatabase($request->attendee_id, $eventId, $request->email_address)) {
                        return $this->error(null, "Email is already registered, please use another email!", 409);
                    }

                    Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                        'email_address' => $request->email_address,
                    ]);

                    $details = [
                        'subject' => 'Your email address has been successfully changed for ' . $attendee->event->full_name,
                        'eventCategory' => $attendee->event->category,
                        'eventYear' => $attendee->event->year,

                        'name' => $attendee->first_name . ' ' . $attendee->last_name,
                        'eventName' => $attendee->event->full_name,
                        'new_email_address' => $request->email_address,
                    ];

                    Mail::to($request->email_address)->send(new EmailChanged($details));
                }

                if ($attendee->username != $request->username) {
                    if (checkAttendeeUsernameIfExistsInDatabase($request->attendee_id, $eventId, $request->username)) {
                        return $this->error(null, "Username is already registered, please use another username!", 409);
                    }

                    Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                        'username' => $request->username,
                    ]);

                    $details = [
                        'subject' => 'Your username has been successfully changed for ' . $attendee->event->full_name,
                        'eventCategory' => $attendee->event->category,
                        'eventYear' => $attendee->event->year,

                        'name' => $attendee->first_name . ' ' . $attendee->last_name,
                        'eventName' => $attendee->event->full_name,
                        'new_username' => $request->username,
                    ];

                    Mail::to($request->email_address)->send(new UsernameChanged($details));
                }

                return $this->success(null, "Attendee Username/Email address updated successfully", 200);
            } else {
                return $this->error(null, "Incorrect attendee password", 401);
            }
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating attendee email/username", 500);
        }
    }

    public function apiAttendeeEditProfilePassword(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'old_password' => 'required',
            'password' => 'required|min:8',
            'confirm_password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            if ($request->password == $request->old_password) {
                return $this->error(null, "Password must be different from the old password", 409);
            }

            if (!Hash::check($request->old_password, Attendee::where('id',  $request->attendee_id)->where('event_id', $eventId)->value('password'))) {
                return $this->error(null, "Old password is incorrect", 401);
            }

            if ($request->password != $request->confirm_password) {
                return $this->error(null, "Password and Confirm password do not match", 409);
            }

            Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->update([
                'password' => Hash::make($request->password),
            ]);

            AttendeePasswordReset::create([
                'event_id' => $eventId,
                'attendee_id' => $request->attendee_id,
                'password_changed_by' => PasswordChangedBy::ATTENDEE->value,
                'password_changed_date_time' => Carbon::now(),
            ]);

            $attendee = Attendee::with('event')->where('id', $request->attendee_id)->first();

            $details = [
                'subject' => 'Password reset for ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,

                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
            ];

            Mail::to($attendee->email_address)->send(new AttendeeResetPassword($details));

            return $this->success(null, "Attendee password updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating attendee password", 500);
        }
    }


    public function apiAttendeeEditPfp(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'pfp' => 'required|image|max:10000',
        ]);

        Log::warning("Image file upload running");

        if ($validator->fails()) {
            Log::warning("Image error validation");
            return $this->errorValidation($validator->errors());
        }

        try {
            if (!$request->hasFile('pfp') || !$request->file('pfp')->isValid()) {
                Log::warning("Image invalid file upload");
                return $this->error(null, "Invalid file upload", 400);
            }

            $filename = pathinfo($request->pfp->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $request->pfp->getClientOriginalExtension();
            $uniqueFilename = $filename . '_' . time() . '_' . Str::random(10) . '.' . $extension;
            $fileDirectory = FileUploadDirectory::ATTENDEES->value;
            $path = $request->pfp->storeAs($fileDirectory, $uniqueFilename, 's3');
            $fileUrl = Storage::disk('s3')->url($path);
            $fileSize = $request->pfp->getSize();
            $dateUploaded = now();

            $width = null;
            $height = null;

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $fileType = finfo_file($finfo, $request->pfp->getRealPath());
            finfo_close($finfo);

            if ($fileType === 'application/octet-stream') {
                $fileType = getMimeTypeByExtension($extension);
            }

            if (str_starts_with($fileType, 'image/')) {
                $imageSize = getimagesize($request->pfp->getRealPath());
                if ($imageSize) {
                    $width = $imageSize[0];
                    $height = $imageSize[1];
                }
            }

            $media = Media::create([
                'file_url' => $fileUrl,
                'file_directory' => $fileDirectory,
                'file_name' => $uniqueFilename,
                'file_type' => $fileType,
                'file_size' => $fileSize,
                'width' => $width,
                'height' => $height,
                'date_uploaded' => $dateUploaded,
            ]);

            Attendee::where('id', $request->attendee_id)->update([
                'pfp_media_id' => $media->id,
            ]);

            return $this->success(null, "Attendee PFP updated successfully", 200);
        } catch (\Exception $e) {
            Log::warning("An error occurred while updating attendee profile");
            return $this->error($e, "An error occurred while updating attendee profile", 500);
        }
    }


    public function apiAttendeeFavorites($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $favoriteSessions = array();
            $favoriteSpeakers = array();
            $favoriteSponsors = array();
            $favoriteExhibitors = array();
            $favoriteMrps = array();
            $favoriteMps = array();

            $attendeeFavoriteSessions = AttendeeFavoriteSession::with('session')->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
            $attendeeFavoriteSpeakers = AttendeeFavoriteSpeaker::with('speaker.pfp')->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
            $attendeeFavoriteSponsors = AttendeeFavoriteSponsor::with(['sponsor.logo', 'sponsor.sponsorType'])->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
            $attendeeFavoriteExhibitors = AttendeeFavoriteExhibitor::with('exhibitor.logo')->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
            $attendeeFavoriteMrps = AttendeeFavoriteMrp::with('meetingRoomPartner.logo')->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();
            $attendeeFavoriteMps = AttendeeFavoriteMp::with('mediaPartner.logo')->where('attendee_id', $attendeeId)->where('event_id', $eventId)->get();

            if ($attendeeFavoriteSessions->isNotEmpty()) {
                foreach ($attendeeFavoriteSessions as $favorite) {
                    if ($favorite->session->is_active) {
                        array_push($favoriteSessions, [
                            'session_id' => $favorite->session->id,
                            'title' => $favorite->session->title,
                            'start_time' => $favorite->session->start_time,
                            'end_time' => $favorite->session->end_time,
                            'session_date' => Carbon::parse($favorite->session->session_date)->format('F d, Y'),
                            'session_week_day' => Carbon::parse($favorite->session->session_date)->format('l'),
                            'session_day' => $favorite->session->session_day,
                        ]);
                    }
                }
            }

            if ($attendeeFavoriteSpeakers->isNotEmpty()) {
                foreach ($attendeeFavoriteSpeakers as $favorite) {
                    if ($favorite->speaker->is_active) {
                        array_push($favoriteSpeakers, [
                            'speaker_id' => $favorite->speaker->id,
                            'salutation' => $favorite->speaker->salutation,
                            'first_name' => $favorite->speaker->first_name,
                            'middle_name' => $favorite->speaker->middle_name,
                            'last_name' => $favorite->speaker->last_name,
                            'company_name' => $favorite->speaker->company_name,
                            'job_title' => $favorite->speaker->job_title,
                            'pfp' => $favorite->speaker->pfp->file_url ?? null,
                        ]);
                    }
                }
            }

            if ($attendeeFavoriteSponsors->isNotEmpty()) {
                foreach ($attendeeFavoriteSponsors as $favorite) {
                    if ($favorite->sponsor->is_active) {
                        array_push($favoriteSponsors, [
                            'sponsor_id' => $favorite->sponsor->id,
                            'name' => $favorite->sponsor->name,
                            'type' => $favorite->sponsor->sponsorType->name ?? null,
                            'logo' => $favorite->sponsor->logo->file_url ?? null,
                        ]);
                    }
                }
            }

            if ($attendeeFavoriteExhibitors->isNotEmpty()) {
                foreach ($attendeeFavoriteExhibitors as $favorite) {
                    if ($favorite->exhibitor->is_active) {
                        array_push($favoriteExhibitors, [
                            'exhibitor_id' => $favorite->exhibitor->id,
                            'name' => $favorite->exhibitor->name,
                            'stand_number' => $favorite->exhibitor->stand_number,
                            'logo' => $favorite->exhibitor->logo->file_url ?? null,
                        ]);
                    }
                }
            }

            if ($attendeeFavoriteMrps->isNotEmpty()) {
                foreach ($attendeeFavoriteMrps as $favorite) {
                    if ($favorite->meetingRoomPartner->is_active) {
                        array_push($favoriteMrps, [
                            'meetingRoomPartner_id' => $favorite->meetingRoomPartner->id,
                            'name' => $favorite->meetingRoomPartner->name,
                            'location' => $favorite->meetingRoomPartner->location,
                            'logo' => $favorite->meetingRoomPartner->logo->file_url ?? null,
                        ]);
                    }
                }
            }

            if ($attendeeFavoriteMps->isNotEmpty()) {
                foreach ($attendeeFavoriteMps as $favorite) {
                    if ($favorite->mediaPartner->is_active) {
                        array_push($favoriteMps, [
                            'mediaPartner_id' => $favorite->mediaPartner->id,
                            'name' => $favorite->mediaPartner->name,
                            'website' => $favorite->mediaPartner->website,
                            'logo' => $favorite->mediaPartner->logo->file_url ?? null,
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
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the favorite details", 500);
        }
    }

    public function apiAttendeesList($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $attendees = Attendee::with('pfp')->where('event_id', $eventId)->where('is_active', true)->get();

            if ($attendees->isEmpty()) {
                return $this->error(null, "There are no attendees yet", 404);
            }

            $data = array();
            foreach ($attendees as $attendee) {
                if ($attendee->id != $attendeeId) {
                    $recipientAttendeeId = $attendee->id;
                    $conversationId = null;

                    $conversation = SingleConversation::where(function ($query) use ($eventId, $attendeeId, $recipientAttendeeId) {
                        $query->where('event_id', $eventId)
                            ->where('created_by_attendee_id', $attendeeId)
                            ->where('recipient_attendee_id', $recipientAttendeeId);
                    })->orWhere(function ($query) use ($eventId, $attendeeId, $recipientAttendeeId) {
                        $query->where('event_id', $eventId)
                            ->where('created_by_attendee_id', $recipientAttendeeId)
                            ->where('recipient_attendee_id', $attendeeId);
                    })->first();

                    if ($conversation) {
                        $conversationId = $conversation->id;
                    }

                    array_push($data, [
                        'attendee_id' => $attendee->id,
                        'salutation' => $attendee->salutation,
                        'first_name' => $attendee->first_name,
                        'middle_name' => $attendee->middle_name,
                        'last_name' => $attendee->last_name,
                        'job_title' => $attendee->job_title,
                        'company_name' => $attendee->company_name,
                        'registration_type' => $attendee->registration_type,
                        'pfp' => $attendee->pfp->file_url ?? null,
                        'conversationId' => $conversationId,
                    ]);
                }
            }
            return $this->success($data, "Attendees list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the attendees list", 500);
        }
    }
}
