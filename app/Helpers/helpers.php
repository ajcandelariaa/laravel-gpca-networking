<?php

use App\Enums\MediaUsageUpdateTypes;
use App\Models\Attendee;
use App\Models\Media;
use App\Models\MediaUsage;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

if (!function_exists('mediaUsageUpdate')) {
    function mediaUsageUpdate($action, $mediaId, $entityType, $entityId, $previousMediaUsageId = null)
    {
        if ($action == MediaUsageUpdateTypes::ADD_ONLY->value) {
            MediaUsage::create([
                'media_id' => $mediaId,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
            ]);
        } else if ($action == MediaUsageUpdateTypes::REMOVED_ONLY->value) {
            $mediaUsage = MediaUsage::find($previousMediaUsageId);

            if ($mediaUsage) {
                $mediaUsage->delete();
            }
        } else {
            if (MediaUsage::find($previousMediaUsageId)) {
                MediaUsage::where('id', $previousMediaUsageId)->update([
                    'media_id' => $mediaId,
                    'entity_type' => $entityType,
                    'entity_id' => $entityId,
                ]);
            }
        }
    }
}

if (!function_exists('getMediaUsageId')) {
    function getMediaUsageId($mediaId, $entityType, $entityId)
    {
        return MediaUsage::where('media_id', $mediaId)->where('entity_type', $entityType)->where('entity_id', $entityId)->value('id');
    }
}

if (!function_exists('getMediaFileList')) {
    function getMediaFileList()
    {
        $mediaFileList = array();
        $mediaFileListTemp = Media::orderBy('date_uploaded', 'DESC')->get();
        if ($mediaFileListTemp->isNotEmpty()) {
            foreach ($mediaFileListTemp as $mediaFile) {
                array_push($mediaFileList, [
                    'id' => $mediaFile->id,
                    'file_url' => $mediaFile->file_url,
                    'file_directory' => $mediaFile->file_directory,
                    'file_name' => $mediaFile->file_name,
                    'file_type' => $mediaFile->file_type,
                    'file_size' => $mediaFile->file_size,
                    'width' => $mediaFile->width,
                    'height' => $mediaFile->height,
                    'date_uploaded' => $mediaFile->date_uploaded,
                ]);
            }
        }

        return $mediaFileList;
    }
}

if (!function_exists('checkAttendeeEmailIfExistsInDatabase')) {
    function checkAttendeeEmailIfExistsInDatabase($attendeeId, $eventId, $emailAddress)
    {
        if ($attendeeId == null) {
            $attendee = Attendee::where('event_id', $eventId)->where('email_address', $emailAddress)->first();
        } else {
            $attendee = Attendee::where('id', '!=', $attendeeId)->where('event_id', $eventId)->where('email_address', $emailAddress)->first();
        }

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('checkAttendeeUsernameIfExistsInDatabase')) {
    function checkAttendeeUsernameIfExistsInDatabase($attendeeId, $eventId, $username)
    {
        if ($attendeeId == null) {
            $attendee = Attendee::where('event_id', $eventId)->where('username', $username)->first();
        } else {
            $attendee = Attendee::where('id', '!=', $attendeeId)->where('event_id', $eventId)->where('username', $username)->first();
        }

        if ($attendee) {
            return true;
        } else {
            return false;
        }
    }
}


if (!function_exists('fetchMembersData')) {
    function fetchMembersData()
    {
        $url = env('API_ENDPOINT') . '/members';
        $response = Http::get($url)->json();

        if ($response['status'] == '200') {
            return $response;
        }
    }
}



if (!function_exists('fetchEventRegistrationTypesData')) {
    function fetchEventRegistrationTypesData($eventCategory, $eventYear)
    {
        $url = env('API_ENDPOINT') . '/event/' . $eventCategory . '/' . $eventYear;
        $response = Http::get($url)->json();

        if ($response['status'] == '200') {
            return $response;
        }
    }
}



if (!function_exists('getMimeTypeByExtension')) {
    function getMimeTypeByExtension($extension)
    {
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'svg' => 'image/svg+xml',
            'webp' => 'image/webp',
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
    }
}



if (!function_exists('generateOTP')) {
    function generateOTP()
    {
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        return $otp;
    }
}

if (!function_exists('sendPushNotification')) {
    function sendPushNotification($deviceToken, $title, $message, $data)
    {
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
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return;
        }
        curl_close($ch);

        $responseData = json_decode($response, true);
        $accessToken = $responseData['access_token'];



        // SENDING NOTIFICATION
        $notification = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'high_importance_channel',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ]
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'content-available' => 1,
                        ]
                    ]
                ],
                'data' => $data,
            ],
        ];

        Log::info(json_encode($notification));

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
            Log::error('Error: ' . curl_error($ch));
        }
        curl_close($ch);
        Log::info($response);
    }
}



if (!function_exists('sendPushNotificationV2')) {
    function sendPushNotificationV2($deviceToken, $title, $message, $data)
    {
        $clientEmail = env('FIREBASE_CLIENT_EMAIL_V2');
        $privateKey = env('FIREBASE_PRIVATE_KEY_V2');
        
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
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
            return;
        }
        curl_close($ch);

        $responseData = json_decode($response, true);
        $accessToken = $responseData['access_token'];



        // SENDING NOTIFICATION
        $notification = [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body' => $message,
                ],
                'android' => [
                    'priority' => 'high',
                    'notification' => [
                        'sound' => 'default',
                        'channel_id' => 'high_importance_channel',
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                    ]
                ],
                'apns' => [
                    'headers' => [
                        'apns-priority' => '10',
                    ],
                    'payload' => [
                        'aps' => [
                            'sound' => 'default',
                            'content-available' => 1,
                            'alert' => [
                                'title' => $title,
                                'body' => $message
                            ],
                        ]
                    ]
                ],
                'data' => $data,
            ],
        ];

        Log::info(json_encode($notification));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/v1/projects/gpca-networking-app-v2/messages:send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $accessToken,    
            'Content-Type: application/json; UTF-8',
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($notification));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            Log::error('Error: ' . curl_error($ch));
        }
        curl_close($ch);
        Log::info($response);
    }
}