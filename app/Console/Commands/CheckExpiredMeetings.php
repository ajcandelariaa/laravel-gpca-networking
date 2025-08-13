<?php

namespace App\Console\Commands;

use App\Enums\MeetingReceiverType;
use App\Enums\MeetingRespondTokenStatus;
use App\Enums\MeetingStatus;
use App\Enums\NotificationTypes;
use App\Mail\AttendeeMeetingExpiredMail;
use App\Models\Attendee;
use App\Models\AttendeeMeeting;
use App\Models\AttendeeNotification;
use App\Models\Exhibitor;
use App\Models\MeetingRoomPartner;
use App\Models\Sponsor;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class CheckExpiredMeetings extends Command
{
    protected $signature   = 'meetings:check-expired';
    protected $description = 'Mark stale meetings as EXPIRED (token or time) and notify requester';

    public function handle(): int
    {
        $now = Carbon::now()->setTimezone('Asia/Dubai');

        AttendeeMeeting::query()
            ->with(['event', 'requester.deviceTokens'])
            ->where('meeting_status', MeetingStatus::PENDING->value)
            ->where(function ($q) use ($now) {
                $q->where(function ($qq) use ($now) {
                    $qq->whereNotNull('respond_token')
                        ->where('respond_token_status', MeetingRespondTokenStatus::ACTIVE->value)
                        ->whereNotNull('respond_token_expires_at')
                        ->where('respond_token_expires_at', '<=', $now);
                })
                    // OR Case B: meeting time passed (use end time; switch to start if you prefer)
                    ->orWhereRaw("TIMESTAMP(meeting_date, meeting_end_time) <= ?", [$now]);
            })
            ->chunkById(300, function ($meetings) use ($now) {
                foreach ($meetings as $m) {
                    DB::beginTransaction();
                    try {
                        $m->meeting_status   = MeetingStatus::EXPIRED->value;
                        $m->expired_datetime = $now;

                        if ($m->respond_token_status === MeetingRespondTokenStatus::ACTIVE->value) {
                            $m->respond_token_status     = MeetingRespondTokenStatus::EXPIRED->value;
                            $m->respond_token_expires_at = $m->respond_token_expires_at ?: $now;
                        }

                        $m->save();
                        DB::commit();
                    } catch (\Throwable $e) {
                        DB::rollBack();
                        Log::warning('Failed marking meeting expired', [
                            'meeting_id' => $m->id,
                            'error'      => $e->getMessage(),
                        ]);
                        continue;
                    }

                    // Notify requester (attendee) — in-app + push + email
                    try {
                        $event        = $m->event;
                        $requester    = $m->requester;
                        $receiverName = $this->resolveReceiverName($m);

                        // In-app notification
                        AttendeeNotification::create([
                            'event_id'        => $event?->id,
                            'attendee_id'     => $m->attendee_id,
                            'notification_id' => null,
                            'type'            => NotificationTypes::ATTENDEE_MEETINGS->value,
                            'title'           => 'Meeting Expired',
                            'subtitle'        => "Your meeting request to {$receiverName} expired without a response.",
                            'message'         => "Your meeting request to {$receiverName} expired without a response.",
                            'sent_datetime'   => $now,
                            'is_seen'         => false,
                        ]);

                        // Push
                        if ($requester && $requester->deviceTokens->isNotEmpty()) {
                            foreach ($requester->deviceTokens as $dt) {
                                $data = [
                                    'event_id'          => (string) ($event?->id),
                                    'notification_type' => NotificationTypes::ATTENDEE_MEETINGS->value,
                                    'entity_id'         => $m->id,
                                ];
                                sendPushNotificationv2(
                                    $dt->device_token,
                                    'Meeting Expired',
                                    "Your meeting request to {$receiverName} expired without a response.",
                                    $data
                                );
                            }
                        }

                        // Email
                        $details = $this->buildDetails($m, $receiverName);
                        if (!empty($requester?->email_address)) {
                            Mail::to($requester->email_address)->send(new AttendeeMeetingExpiredMail($details, /*isReceiver*/ false));
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Failed to notify requester about expiry', [
                            'meeting_id' => $m->id,
                            'error'      => $e->getMessage(),
                        ]);
                    }
                }
            });

        $this->info('✅ Expiry sweep complete.');
        return self::SUCCESS;
    }

    /**
     * Build a details array consistent with your other Mailables.
     */
    protected function buildDetails(AttendeeMeeting $m, ?string $receiverName): array
    {
        $event = $m->event;

        return [
            'requesterName'    => $m->requester?->first_name,
            'receiverName'     => $receiverName ?? 'recipient',
            'receiverType'     => $m->receiver_type,

            'eventName'        => $event?->full_name,
            'eventCategory'    => $event?->category,
            'eventLink'        => $event?->event_full_link,

            'meetingTitle'     => $m->meeting_title,
            'meetingDate'      => Carbon::parse($m->meeting_date)->format('F d, Y'),
            'meetingStartTime' => Carbon::parse($m->meeting_start_time)->format('g:i A'),
            'meetingEndTime'   => Carbon::parse($m->meeting_end_time)->format('g:i A'),
            'meetingLocation'  => $m->meeting_location,
            'meetingNotes'     => $m->meeting_notes,

            'isAttendee'       => $m->receiver_type === MeetingReceiverType::ATTENDEE->value,
            'meetingRespondLink' => null,
        ];
    }

    /**
     * Resolve receiver display name with minimal selects.
     */
    protected function resolveReceiverName(AttendeeMeeting $m): string
    {
        return match ($m->receiver_type) {
            MeetingReceiverType::ATTENDEE->value => optional(Attendee::select('first_name')->find($m->receiver_id))->first_name ?? 'the attendee',
            MeetingReceiverType::EXHIBITOR->value => optional(Exhibitor::select('contact_person_name', 'name')->find($m->receiver_id))->contact_person_name
                ?: optional(Exhibitor::select('name')->find($m->receiver_id))->name
                ?: 'the exhibitor',
            MeetingReceiverType::SPONSOR->value => optional(Sponsor::select('contact_person_name', 'name')->find($m->receiver_id))->contact_person_name
                ?: optional(Sponsor::select('name')->find($m->receiver_id))->name
                ?: 'the sponsor',
            MeetingReceiverType::MEETING_ROOM_PARTNER->value => optional(MeetingRoomPartner::select('contact_person_name', 'name')->find($m->receiver_id))->contact_person_name
                ?: optional(MeetingRoomPartner::select('name')->find($m->receiver_id))->name
                ?: 'the meeting room partner',
            default => 'the recipient',
        };
    }
}
