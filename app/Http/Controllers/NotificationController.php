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
            'atendee_notification_id' => 'required|exists:attendee_notifications,id',
        ]);

        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            AttendeeNotification::where('id', $request->atendee_notification_id)->update([
                'is_seen' => true,
                'seen_datetime' => Carbon::now(),
            ]);

            return $this->success(null, "Notification status updated successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while updating the notification status", 500);
        }
    }
}
