<?php

use App\Enums\MediaUsageUpdateTypes;
use App\Models\Attendee;
use App\Models\Media;
use App\Models\MediaUsage;
use GuzzleHttp\Client as GuzzleHttpClient;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Http;
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



if (!function_exists('pushNotification')) {
    function pushNotification($to, $notification, $data)
    {
        $server_key = env('FIREBASE_SERVER_KEY');

        $url = "https://fcm.googleapis.com/fcm/send";
        $fields = json_encode(array(
            'to' => $to,
            'notification' => $notification,
            'data' => $data,
        ));

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, ($fields));

        $headers = array();
        $headers[] = 'Authorization: key =' . $server_key;
        $headers[] = 'Content-type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error: ' . curl_error($ch);
        }
        curl_close($ch);
    }
}

if (!function_exists('sendWhatsAppMessage')) {
    function sendWhatsAppMessage($to, $message)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $twilioNumber = env('TWILIO_NUMBER');

        $client = new Client($sid, $token);
        try {
            $client->messages->create(
                $to,
                [
                    'from' => $twilioNumber,
                    'body' => $message
                ]
            );
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }
}

if (!function_exists('sendMessageUsingSMSCountry')) {
    function sendMessageUsingSMSCountry($to, $message)
    {
        $auth_key = env('SMS_AUTH_KEY');
        $auth_token = env('SMS_AUTH_TOKEN');

        $combined = $auth_key . ":" . $auth_token;
        $formatted = base64_encode($combined);

        $client = new GuzzleHttpClient();

        $url = "https://restapi.smscountry.com/v0.1/Accounts/$auth_key/SMSes/";

        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . $formatted,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode([
                    'Text' => $message,
                    'Number' => $to,
                    'SenderId' => 'SMSCountry',
                    'DRNotifyUrl' => 'https://www.domainname.com/notifyurl',
                    'DRNotifyHttpMethod' => 'POST',
                    'Tool' => 'API',
                ]),
            ]);

            $responseBody = $response->getBody()->getContents();
            return json_decode($responseBody, true);
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $responseBody = $response->getBody()->getContents();
                return [
                    'error' => 'RequestException',
                    'message' => json_decode($responseBody, true)
                ];
            } else {
                return [
                    'error' => 'RequestException',
                    'message' => $e,
                ];
            }
        }
    }
}

