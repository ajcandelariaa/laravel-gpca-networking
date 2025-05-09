<?php

namespace App\Console;

use App\Models\Attendee;
use App\Models\AttendeeNotification;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info('Scheduler is running.');

            $eventId = 4;
            $notifications = Notification::with('event')->where('event_id', $eventId)->where('is_sent', false)->get();

            if ($notifications->isNotEmpty()) {
                foreach ($notifications as $notification) {
                    $now = Carbon::now()->setTimezone($notification->event->timezone);
                    $sendTime = $notification->send_datetime;

                    if ($now->greaterThanOrEqualTo($sendTime)) {
                        Attendee::with('deviceTokens')->where('event_id', $notification->event->id)->where('is_active', true)
                            ->chunk(50, function ($attendeesChunk) use ($notification, $now) {
                                $notificationsToInsert = [];
                                foreach ($attendeesChunk as $attendee) {
                                    try {
                                        if ($attendee->deviceTokens->isNotEmpty()) {
                                            foreach ($attendee->deviceTokens as $attendeeDeviceToken) {
                                                $data = [
                                                    'event_id' => (string) $notification->event->id,
                                                    'notification_type' => $notification->type,
                                                    'entity_id' => null,
                                                ];
                                                sendPushNotificationv2($attendeeDeviceToken->device_token, $notification->title, $notification->message, $data);
                                            }
                                        }

                                        $notificationsToInsert[] = [
                                            'event_id' => $notification->event->id,
                                            'attendee_id' => $attendee->id,
                                            'notification_id' => $notification->id,
                                            'sent_datetime' => $now,
                                            'created_at' => now(),
                                            'updated_at' => now(),
                                        ];
                                    } catch (\Exception $e) {
                                        Log::info('Error: ' . $e);
                                    }
                                }
                                if (!empty($notificationsToInsert)) {
                                    AttendeeNotification::insert($notificationsToInsert);
                                }
                            });
                        $notification->update(['is_sent' => true]);
                        Log::info("✅ Notification ID {$notification->id} marked as sent.");
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
