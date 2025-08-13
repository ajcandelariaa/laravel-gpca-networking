<?php

namespace App\Http\Controllers;

use App\Enums\MeetingReceiverType;
use App\Enums\MeetingRespondTokenStatus;
use App\Models\AttendeeMeeting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Models\AttendeeNotification;
use App\Mail\AttendeeMeetingAcceptedMail;
use App\Mail\AttendeeMeetingDeclinedMail;
use App\Enums\MeetingStatus;
use App\Enums\NotificationTypes;
use App\Models\Exhibitor;
use App\Models\MeetingRoomPartner;
use App\Models\Sponsor;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MeetingResponseController extends Controller
{
    public function meetingRespondView($eventCategory, $eventId, $meetingId, $token)
    {
        $meeting = AttendeeMeeting::with(['event', 'requester'])
            ->where('id', $meetingId)
            ->where('event_id', $eventId)
            ->first();

        if (!$meeting) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if ($meeting->receiver_type === MeetingReceiverType::ATTENDEE->value) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if (empty($meeting->respond_token) || $meeting->respond_token !== $token) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if ($meeting->respond_token_status !== MeetingRespondTokenStatus::ACTIVE->value) {
            return response()->view('meeting.already_responded', ['meeting' => $meeting], 410);
        }

        if (!$meeting->respond_token_expires_at || Carbon::now()->greaterThan(Carbon::parse($meeting->respond_token_expires_at))) {
            return response()->view('meeting.expired_token', ['meeting' => $meeting], 410);
        }

        if ($meeting->meeting_status !== MeetingStatus::PENDING->value) {
            return response()->view('meeting.already_responded', ['meeting' => $meeting], 410);
        }

        return view('meeting.response_form', [
            'meeting' => $meeting,
            'token' => $token,
            'eventId' => $eventId,
            'eventCategory' => $eventCategory,
        ]);
    }

    public function meetingRespond(Request $request, $eventCategory, $eventId, $meetingId)
    {
        $validated = $request->validate([
            'token' => 'required|string',
            'action' => 'required|in:accept,decline',
            'message' => 'required|string|max:500',
        ]);

        $token = $validated['token'];
        $action  = $validated['action'];
        $message = $validated['message'];

        $meeting = AttendeeMeeting::with(['event', 'requester'])
            ->where('id', $meetingId)
            ->where('event_id', $eventId)
            ->lockForUpdate()
            ->first();

        if (!$meeting) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if ($meeting->receiver_type === MeetingReceiverType::ATTENDEE->value) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if (empty($meeting->respond_token) || $meeting->respond_token !== $token) {
            return response()->view('meeting.invalid_link', [], 404);
        }

        if ($meeting->respond_token_status !== MeetingRespondTokenStatus::ACTIVE->value) {
            return response()->view('meeting.already_responded', ['meeting' => $meeting], 410);
        }

        if (!$meeting->respond_token_expires_at || Carbon::now()->greaterThan(Carbon::parse($meeting->respond_token_expires_at))) {
            return response()->view('meeting.expired_token', ['meeting' => $meeting], 410);
        }

        if ($meeting->meeting_status !== MeetingStatus::PENDING->value) {
            return response()->view('meeting.already_responded', ['meeting' => $meeting], 410);
        }

        DB::beginTransaction();
        try {
            if ($action === 'accept') {
                $meeting->meeting_status = MeetingStatus::ACCEPTED->value;
                $meeting->accepted_reason = $message;
                $meeting->accepted_datetime = Carbon::now();
            } else {
                $meeting->meeting_status = MeetingStatus::DECLINED->value;
                $meeting->declined_reason = $message;
                $meeting->declined_datetime = Carbon::now();
            }

            $meeting->respond_token_status = MeetingRespondTokenStatus::USED->value;
            $meeting->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Meeting respond failed', [
                'meeting_id' => $meeting->id,
                'event_id' => $eventId,
                'token_tail' => substr($token, -6),
                'error' => $e->getMessage(),
            ]);
            return response()->view('meeting.processing_error', [], 500);
        }

        $event = $meeting->event;
        $requester = $meeting->requester;
        $receiverEmail = $this->resolveReceiverEmail($meeting);
        $receiverName = $this->resolveReceiverName($meeting);

        $details = [
            'requesterName' => $requester?->first_name,
            'receiverName' => $receiverName,
            'receiverType' => $meeting->receiver_type,

            'eventName' => $event?->full_name,
            'eventCategory' => $event?->category,
            'eventLink' => $event?->event_full_link,

            'meetingTitle' => $meeting->meeting_title,
            'meetingDate' => Carbon::parse($meeting->meeting_date)->format('F d, Y'),
            'meetingStartTime' => Carbon::parse($meeting->meeting_start_time)->format('g:i A'),
            'meetingEndTime' => Carbon::parse($meeting->meeting_end_time)->format('g:i A'),
            'meetingLocation' => $meeting->meeting_location,
            'meetingNotes' => $meeting->meeting_notes,

            'declinedReason' => $action === 'decline' ? $message : null,
            'isAttendee' => $meeting->receiver_type === MeetingReceiverType::ATTENDEE->value,
            'meetingRespondLink' => null,
        ];

        try {
            if ($action === 'accept') {
                AttendeeNotification::create([
                    'event_id' => $event->id,
                    'attendee_id' => $meeting->attendee_id,
                    'notification_id' => null,
                    'type' => NotificationTypes::ATTENDEE_MEETING_DETAILS->value,
                    'title' => "Meeting Request Accepted",
                    'subtitle' => "Your meeting request has been accepted by {$receiverName}.",
                    'message' => "Your meeting request has been accepted by {$receiverName}.",
                    'sent_datetime' => now(),
                    'is_seen' => false,
                ]);

                if ($requester && $requester->deviceTokens->isNotEmpty()) {
                    foreach ($requester->deviceTokens as $dt) {
                        $data = [
                            'event_id' => (string) $event->id,
                            'notification_type' => NotificationTypes::ATTENDEE_MEETING_DETAILS->value,
                            'entity_id' => $meeting->id,
                        ];
                        sendPushNotificationv2(
                            $dt->device_token,
                            "Meeting Request Accepted",
                            "Your meeting request has been accepted by {$receiverName}.",
                            $data
                        );
                    }
                }
            } else {
                AttendeeNotification::create([
                    'event_id' => $event->id,
                    'attendee_id' => $meeting->attendee_id,
                    'notification_id' => null,
                    'type' => NotificationTypes::ATTENDEE_MEETINGS->value,
                    'title' => "Meeting Request Declined",
                    'subtitle' => "{$receiverName} declined your meeting request.",
                    'message' => "{$receiverName} declined your meeting request.",
                    'sent_datetime' => now(),
                    'is_seen' => false,
                ]);

                if ($requester && $requester->deviceTokens->isNotEmpty()) {
                    foreach ($requester->deviceTokens as $dt) {
                        $data = [
                            'event_id' => (string) $event->id,
                            'notification_type' => NotificationTypes::ATTENDEE_MEETINGS->value,
                            'entity_id' => $meeting->id,
                        ];
                        sendPushNotificationv2(
                            $dt->device_token,
                            "Meeting Request Declined",
                            "{$receiverName} declined your meeting request.",
                            $data
                        );
                    }
                }
            }

            if ($action === 'accept') {
                if (!empty($receiverEmail)) {
                    Mail::to($receiverEmail)->send(new AttendeeMeetingAcceptedMail($details, true));
                }
                if (!empty($requester?->email_address)) {
                    Mail::to($requester->email_address)->send(new AttendeeMeetingAcceptedMail($details, false));
                }
            } else {
                if (!empty($receiverEmail)) {
                    Mail::to($receiverEmail)->send(new AttendeeMeetingDeclinedMail($details, true));
                }
                if (!empty($requester?->email_address)) {
                    Mail::to($requester->email_address)->send(new AttendeeMeetingDeclinedMail($details, false));
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Post-response notifications/email failed', [
                'meeting_id' => $meeting->id,
                'error'      => $e->getMessage(),
            ]);
        }

        return view('meeting.thank_you', [
            'meeting' => $meeting,
            'status'  => $meeting->meeting_status,
        ]);
    }

    protected function resolveReceiverEmail(AttendeeMeeting $meeting): ?string
    {
        switch ($meeting->receiver_type) {
            case MeetingReceiverType::EXHIBITOR->value:
                $m = Exhibitor::find($meeting->receiver_id);
                return $m?->email_address ?: null;

            case MeetingReceiverType::SPONSOR->value:
                $m = Sponsor::find($meeting->receiver_id);
                return $m?->email_address ?: null;

            case MeetingReceiverType::MEETING_ROOM_PARTNER->value:
                $m = MeetingRoomPartner::find($meeting->receiver_id);
                return $m?->email_address ?: null;

            default:
                return null;
        }
    }

    protected function resolveReceiverName(AttendeeMeeting $meeting): ?string
    {
        switch ($meeting->receiver_type) {
            case MeetingReceiverType::EXHIBITOR->value:
                $m = Exhibitor::find($meeting->receiver_id);
                return $m?->contact_person_name ?: $m?->name;

            case MeetingReceiverType::SPONSOR->value:
                $m = Sponsor::find($meeting->receiver_id);
                return $m?->contact_person_name ?: $m?->name;

            case MeetingReceiverType::MEETING_ROOM_PARTNER->value:
                $m = MeetingRoomPartner::find($meeting->receiver_id);
                return $m?->contact_person_name ?: $m?->name;

            default:
                return null;
        }
    }
}
