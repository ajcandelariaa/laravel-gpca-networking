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
        $deviceToken = 'dao5nP2ZR12OPKQ0r9L6Zl:APA91bHzqTH1bVpH2afvhdP8PKGT7D3r2vA4Y4oxbB7lhULB0T5ut8mYxyfJ61eJnn82ZRLN0OT7MksBWrPeYckn7ngkuXonFIrwNqII2TDfFpO437pXHvehCYZAK0YTVzvko2bl1lg6';
        sendPushNotification2($deviceToken, 'Test title', 'Test message', null);
    }
}
