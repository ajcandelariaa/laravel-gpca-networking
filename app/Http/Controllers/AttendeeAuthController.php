<?php

namespace App\Http\Controllers;

use App\Enums\PasswordChangedBy;
use App\Mail\AttendeeAccountActivatedMail;
use App\Mail\AttendeeActivationAccountMail;
use App\Mail\AttendeeResetPassword;
use App\Mail\ForgotPasswordOtp;
use Illuminate\Http\Request;
use App\Models\Attendee;
use App\Models\AttendeeOtpCode;
use App\Models\AttendeePasswordReset;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AttendeeAuthController extends Controller
{
    use HttpResponses;

    public function apiActivateAccountSendOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
        ]);


        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {

            $attendee = Attendee::with('event')->where('event_id', $eventId)->where('email_address', $request->email_address)->first();

            if (!$attendee) {
                return $this->error(null, "Email address doesn't exist", 404);
            }


            if (!empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is already activated", 409);
            }

            AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'activation')
                ->where('is_used', false)
                ->update([
                    'expires_datetime' => Carbon::now(),
                ]);

            $otp = generateOTP();

            AttendeeOtpCode::create([
                'event_id'  => $eventId,
                'attendee_id' => $attendee->id,
                'purpose' => 'activation',
                'code_hash' => Hash::make($otp),
                'expires_datetime' => Carbon::now()->addMinutes(10),
                'is_used' => false,
                'used_datetime' => null,
                'attempts' => 0,
            ]);

            $details = [
                'subject'   => 'Activate your Networking App Account for the ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,

                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
                'otp' => $otp,
            ];

            Mail::to($attendee->email_address)->send(new AttendeeActivationAccountMail($details));

            return $this->success(['attendee_id' => $attendee->id], "OTP sent successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while sending the OTP", 500);
        }
    }

    public function apiActivateAccountVerifyOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::where('event_id', $eventId)->where('id', $request->attendee_id)->first();

            if (!$attendee) {
                return $this->error(null, "Attendee doesn't exist", 404);
            }

            if (!empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is already activated", 409);
            }

            $otpRow = AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'activation')
                ->where('is_used', false)
                ->orderByDesc('id')
                ->first();

            if (!$otpRow) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if (Carbon::now()->gt(Carbon::parse($otpRow->expires_datetime))) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if ($otpRow->attempts >= 5) {
                return $this->error(null, "Too many attempts. Request a new code.", 429);
            }

            if (!Hash::check($request->otp, $otpRow->code_hash)) {
                $otpRow->attempts = $otpRow->attempts + 1;
                $otpRow->save();

                return $this->error(null, "Invalid or expired code.", 400);
            }

            $otpRow->is_used = true;
            $otpRow->used_datetime = Carbon::now();
            $otpRow->save();

            return $this->success(['attendee_id' => $attendee->id, 'otp_id' => $otpRow->id], "OTP verified", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while verifying the OTP", 500);
        }
    }

    public function apiActivateAccountSetPassword(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'otp_id' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::where('id', $request->attendee_id)->where('event_id', $eventId)->first();

            if (!$attendee) {
                return $this->error(null, "Attendee doesn't exist", 404);
            }

            if (!empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is already activated", 409);
            }

            $latestUsedOtp = AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'activation')
                ->where('is_used', true)
                ->orderByDesc('id')
                ->first();

            if (!$latestUsedOtp) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if ((int)$request->otp_id !== (int)$latestUsedOtp->id) {
                return $this->error(null, "OTP conflict.", 409);
            }

            $attendee->password = Hash::make($request->password);
            $attendee->password_set_datetime = Carbon::now();
            $attendee->save();

            AttendeePasswordReset::create([
                'event_id' => $eventId,
                'attendee_id' => $request->attendee_id,
                'password_changed_by' => PasswordChangedBy::ATTENDEE->value,
                'password_changed_date_time' => Carbon::now(),
            ]);

            $details = [
                'subject' => 'Your Networking App Account for the ' . $attendee->event->full_name . ' Has Been Activated',
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,
                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
            ];

            Mail::to($attendee->email_address)->send(new AttendeeAccountActivatedMail($details));

            return $this->success(null, "Account activated", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while setting the password", 500);
        }
    }



    // FORGOT PASSWORD
    public function apiForgotPasswordSendOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'email_address' => 'required|email',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $email = mb_strtolower(trim($request->email_address));
            $attendee = Attendee::with('event')->where('event_id', $eventId)->whereRaw('LOWER(email_address) = ?', [$email])->first();

            if (!$attendee) {
                return $this->error(null, "Email address doesn't exist", 404);
            }

            if (empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is not activated. Please activate your account first.", 409);
            }

            AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'reset')
                ->where('is_used', false)
                ->update([
                    'expires_datetime' => Carbon::now(),
                ]);

            $otp = generateOTP();

            AttendeeOtpCode::create([
                'event_id'  => $eventId,
                'attendee_id' => $attendee->id,
                'purpose' => 'reset',
                'code_hash' => Hash::make($otp),
                'expires_datetime' => Carbon::now()->addMinutes(10),
                'is_used' => false,
                'used_datetime' => null,
                'attempts' => 0,
            ]);

            $details = [
                'subject' => 'Your password reset code for ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,

                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
                'otp' => $otp,
            ];

            Mail::to($attendee->email_address)->send(new ForgotPasswordOtp($details));

            return $this->success(['attendee_id' => $attendee->id], "OTP sent successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while sending the OTP", 500);
        }
    }


    public function apiForgotPasswordVerifyOtp(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::where('event_id', $eventId)->where('id', $request->attendee_id)->first();

            if (!$attendee) {
                return $this->error(null, "Attendee doesn't exist", 404);
            }

            if (empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is not activated. Please activate your account first.", 409);
            }

            $otpRow = AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'reset')
                ->where('is_used', false)
                ->orderByDesc('id')
                ->first();

            if (!$otpRow) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if (Carbon::now()->gt(Carbon::parse($otpRow->expires_datetime))) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if ($otpRow->attempts >= 5) {
                return $this->error(null, "Too many attempts. Request a new code.", 429);
            }

            if (!Hash::check($request->otp, $otpRow->code_hash)) {
                $otpRow->attempts = $otpRow->attempts + 1;
                $otpRow->save();

                return $this->error(null, "Invalid or expired code.", 400);
            }

            $otpRow->is_used = true;
            $otpRow->used_datetime = Carbon::now('UTC');
            $otpRow->save();

            return $this->success(['attendee_id' => $attendee->id, 'otp_id' => $otpRow->id], "OTP verified", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while verifying the code", 500);
        }
    }

    public function apiForgotPasswordSetPassword(Request $request, $apiCode, $eventCategory, $eventId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'otp_id' => 'required',
            'password' => 'required|min:8',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            $attendee = Attendee::with('event')->where('event_id', $eventId)->where('id', $request->attendee_id)->first();

            if (!$attendee) {
                return $this->error(null, "Attendee doesn't exist", 404);
            }

            if (empty($attendee->password_set_datetime)) {
                return $this->error(null, "Account is not activated. Please activate your account first.", 409);
            }

            $latestUsedOtp = AttendeeOtpCode::where('event_id', $eventId)
                ->where('attendee_id', $attendee->id)
                ->where('purpose', 'reset')
                ->where('is_used', true)
                ->orderByDesc('id')
                ->first();

            if (!$latestUsedOtp) {
                return $this->error(null, "Invalid or expired code.", 400);
            }

            if ((int)$request->otp_id !== (int)$latestUsedOtp->id) {
                return $this->error(null, "OTP conflict.", 409);
            }

            $attendee->password = Hash::make($request->password);
            $attendee->save();

            AttendeePasswordReset::create([
                'event_id' => $eventId,
                'attendee_id' => $request->attendee_id,
                'password_changed_by' => PasswordChangedBy::ATTENDEE->value,
                'password_changed_date_time' => Carbon::now(),
            ]);

            $details = [
                'subject' => 'Your Networking App password was changed for ' . $attendee->event->full_name,
                'eventCategory' => $attendee->event->category,
                'eventYear' => $attendee->event->year,
                'name' => $attendee->first_name . ' ' . $attendee->last_name,
                'eventName' => $attendee->event->full_name,
            ];
            Mail::to($attendee->email_address)->send(new AttendeeResetPassword($details));

            return $this->success(null, "Password reset successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while setting the new password", 500);
        }
    }
}
