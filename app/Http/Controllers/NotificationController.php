<?php

namespace App\Http\Controllers;

use App\Models\AttendeeNotification;
use App\Models\Event;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    use HttpResponses;

    public function eventNotificationsView($eventCategory, $eventId)
    {
        $eventName = Event::where('id', $eventId)->where('category', $eventCategory)->value('full_name');

        return view('admin.event.notifications.notifications', [
            "pageTitle" => "Notifications",
            "eventName" => $eventName,
            "eventCategory" => $eventCategory,
            "eventId" => $eventId,
        ]);
    }




    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiEventNotificationMarkAsRead(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required|exists:attendees,id',
            'attendee_notification_id' => 'required|exists:attendee_notifications,id',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            AttendeeNotification::where('id', $request->attendee_notification_id)->update([
                'is_seen' => true,
                'seen_datetime' => Carbon::now(),
            ]);

            return $this->success(null, "Notification status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the notification status", 500);
        }
    }

    public function testPushNotification(){
        $clientEmail = env('FIREBASE_CLIENT_EMAIL');
        $privateKey = env('FIREBASE_PRIVATE_KEY');

        // Define JWT header and payload
        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT' ]);
        $now = time();
        $expiration = $now + 3600; // 1 hour expiration
        $payload = json_encode([
            'iss' => $clientEmail,
            'scope' => 'https://www.googleapis.com/auth/cloud-platform https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $expiration,
            'iat' => $now,
        ]);

        // Encode to base64
        $base64UrlHeader = str_replace(['+', '/' , '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/' , '='], ['-', '_', ''], base64_encode($payload));

        // Create the signature
        $signatureInput = $base64UrlHeader . "." . $base64UrlPayload;
        openssl_sign($signatureInput, $signature, $privateKey, 'sha256');
        $base64UrlSignature = str_replace(['+', '/' , '='], ['-', '_', ''], base64_encode($signature));

        // Create the JWT
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        // Exchange JWT for an access token
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://oauth2.googleapis.com/token');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]));

        $response = curl_exec($ch);
        curl_close($ch);

        $responseData = json_decode($response, true);
        $accessToken = $responseData['access_token'];

        $notification = [
            'message' => [
                'token' => 'dao5nP2ZR12OPKQ0r9L6Zl:APA91bHzqTH1bVpH2afvhdP8PKGT7D3r2vA4Y4oxbB7lhULB0T5ut8mYxyfJ61eJnn82ZRLN0OT7MksBWrPeYckn7ngkuXonFIrwNqII2TDfFpO437pXHvehCYZAK0YTVzvko2bl1lg6',
                'notification' => [
                    'title' => 'Test from the backend',
                    'body' => 'Sample test from the gpca networking app backend using the latest device token',
                ],
            ],
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/gpca-networking-app-91813/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json; UTF-8',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        echo $response;

    }
}
