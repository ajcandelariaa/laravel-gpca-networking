<?php

namespace App\Http\Controllers;

use App\Enums\NotificationTypes;
use App\Events\MessageSent;
use App\Models\Attendee;
use App\Models\SingleConversation;
use App\Models\SingleConversationMessage;
use App\Traits\HttpResponses;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConversationController extends Controller
{
    use HttpResponses;

    // =========================================================
    //                       API FUNCTIONS
    // =========================================================
    public function apiConversationsList($apiCode, $eventCategory, $eventId, $attendeeId)
    {
        try {
            $conversations = SingleConversation::where(function ($query) use ($eventId, $attendeeId) {
                $query->where('event_id', $eventId)
                    ->where('created_by_attendee_id', $attendeeId);
            })->orWhere(function ($query) use ($eventId, $attendeeId) {
                $query->where('event_id', $eventId)
                    ->where('recipient_attendee_id', $attendeeId);
            })->orderBy('updated_at', 'DESC')->get();

            $data = array();
            if ($conversations->isNotEmpty()) {
                foreach ($conversations as $conversation) {
                    if ($conversation->created_by_attendee_id != $attendeeId) {
                        $otherAttendee = Attendee::with('pfp')->where('id', $conversation->created_by_attendee_id)->first();
                    } else {
                        $otherAttendee = Attendee::with('pfp')->where('id', $conversation->recipient_attendee_id)->first();
                    }

                    $countUnreadMessages = SingleConversationMessage::where('single_conversation_id', $conversation->id)->where('attendee_id', $otherAttendee->id)->where('is_seen', false)->count();

                    $lastConversationMessage = SingleConversationMessage::where('single_conversation_id', $conversation->id)->orderBy('created_at', 'DESC')->value('message');

                    array_push($data, [
                        'conversation_id' => $conversation->id,
                        'conversation_type' => 'single',
                        'attendee_details' => [
                            'attendee_id' => $otherAttendee->id,
                            'name' => $otherAttendee->first_name . ' ' . $otherAttendee->last_name,
                            'pfp' => $otherAttendee->pfp->file_url ?? null,
                        ],
                        'unread_messages_count' => $countUnreadMessages,
                        'last_conversation_message' => $lastConversationMessage,
                    ]);
                }
            }
            return $this->success($data, "Conversation list", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while getting the list of conversations", 500);
        }
    }

    public function apiConversationMessages($apiCode, $eventCategory, $eventId, $attendeeId, $conversationId)
    {
        $data = array();
        $dataMessages = array();

        $messages = SingleConversationMessage::where('single_conversation_id', $conversationId)->orderBy('created_at', 'ASC')->get();
        foreach ($messages as $message) {
            if ($message->attendee_id == $attendeeId) {
                array_push($dataMessages, [
                    'message_id' => $message->id,
                    'message_by_attendee_id' => $message->attendee_id,
                    'message' => $message->message,
                    'date' => Carbon::parse($message->updated_at)->format('F d, Y'),
                    'time' => Carbon::parse($message->updated_at)->toTimeString(),
                ]);
            } else {
                array_push($dataMessages, [
                    'message_id' => $message->id,
                    'message_by_attendee_id' => $message->attendee_id,
                    'message' => $message->message,
                    'date' => Carbon::parse($message->updated_at)->format('F d, Y'),
                    'time' => Carbon::parse($message->updated_at)->toTimeString(),
                ]);
            }
        }

        $data = [
            'conversation_id' => $conversationId,
            'conversation_type' => 'single',
            'messages' => $dataMessages
        ];
        
        return $this->success($data, "Conversation messages", 200);
    }

    public function apiConversationSendMessage(Request $request, $apiCode, $eventCategory, $eventId, $attendeeId)
    {
        $validator = Validator::make($request->all(), [
            'attendee_id' => 'required',
            'recipient_attendee_id' => 'required',
            'message' => 'required',
            'conversation_id' => 'nullable',
        ]);
        
        if ($validator->fails()) {
            return $this->errorValidation($validator->errors());
        }

        try {
            if ($request->conversation_id == null) {
                $conversation = SingleConversation::create([
                    'event_id' => $eventId,
                    'created_by_attendee_id' => $request->attendee_id,
                    'recipient_attendee_id' => $request->recipient_attendee_id,
                ]);

                $finalConversationId = $conversation->id;
            } else {
                SingleConversation::where('id', $request->conversation_id)->update([
                    'updated_at' => Carbon::now(),
                ]);
                $finalConversationId = $request->conversation_id;
            }

            $message = SingleConversationMessage::create([
                'single_conversation_id' => $finalConversationId,
                'attendee_id' => $request->attendee_id,
                'message' => $request->message,
            ]);

            $data = [
                'single_conversation_id' => intval($finalConversationId),
                'message_id' => $message->id,
                'message_by_attendee_id' => $message->id,
                'message' => $message->message,
                'date' => Carbon::parse($message->updated_at)->format('F d, Y'),
                'time' => Carbon::parse($message->updated_at)->toTimeString(),
            ];

            $attendee = Attendee::with('deviceTokens')->where('id', $request->recipient_attendee_id)->first();
            if($attendee->deviceTokens->isNotEmpty()){
                foreach($attendee->deviceTokens as $deviceToken){
                    $data2 = [
                        'event_id' => $eventId,
                        'notification_type' => NotificationTypes::ATTENDEE_CHATS->value,
                        'entity_id' => null,
                    ];

                    // sendPushNotification($deviceToken, "New message", $message->message, $data2);
                }
            }

            // broadcast(new MessageSent($data))->toOthers();

            return $this->success($data, "Message sent successfully", 200);
        } catch (\Exception $e) {
            return $this->error($e, "An error occurred while sending a message", 500);
        }
    }
}
