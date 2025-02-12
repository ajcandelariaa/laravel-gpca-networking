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
    public function apiEventNofications($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $attendeeNotifications = AttendeeNotification::with('notification')->where('event_id', $eventId)->where('attendee_id', $attendeeId)->orderBy('sent_datetime', 'DESC')->get();

            if ($attendeeNotifications->isEmpty()) {
                return null;
            }

            $data = array();
            foreach ($attendeeNotifications as $attendeeNotification) {
                if ($attendeeNotification->notification_id != null) {
                    array_push($data, [
                        'id' => $attendeeNotification->id,
                        'type' => $attendeeNotification->notification->type,
                        'title' => $attendeeNotification->notification->title,
                        'subtitle' => $attendeeNotification->notification->subtitle,
                        'message' => $attendeeNotification->notification->message,
                        'sent_datetime' => Carbon::parse($attendeeNotification->sent_datetime)->format('M j, Y g:i A'),
                        'is_seen' => $attendeeNotification->is_seen ? true : false,
                        'seen_datetime' => $attendeeNotification->seen_datetime,
                    ]);
                } else {
                    array_push($data, [
                        'id' => $attendeeNotification->id,
                        'type' => $attendeeNotification->type,
                        'title' => $attendeeNotification->title,
                        'subtitle' => $attendeeNotification->subtitle,
                        'message' => $attendeeNotification->message,
                        'sent_datetime' => Carbon::parse($attendeeNotification->sent_datetime)->format('M j, Y g:i A'),
                        'is_seen' => $attendeeNotification->is_seen ? true : false,
                        'seen_datetime' => $attendeeNotification->seen_datetime,
                    ]);
                }
            }
            return $this->success($data, "Notification list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the notification list", 500);
        }
    }

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

    public function testPushNotification()
    {
        $deviceToken = 'dtU46xoOQaKemJ8_4oIyXF:APA91bETtxzes8RRUZbY1Vy9DOcyleJXopKOBU920T3cTBZ0tP22J0yVG-dg_l6hDc26ITqFsjPo2lYHGT8GuPy5kGVyVV7FHfKQENkVxFVmn8oJwemn3hxEUTJwLeEUOyFL-trJYJKW';
        sendPushNotification($deviceToken, 'Test title5', 'Test message5', null);
    }
}
