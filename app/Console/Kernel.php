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
            $eventId = 1;

            $notifications = Notification::with('event')->where('event_id', $eventId)->where('is_sent', false)->get();

            if ($notifications->isNotEmpty()) {
                foreach ($notifications as $notification) {
                    $now = Carbon::now()->setTimezone($notification->event->timezone);
                    $sendTime = $notification->send_datetime;
                    if ($now->greaterThanOrEqualTo($sendTime)) {

                        // ADD ATTENDEENOTIFICATION
                        $attendees = Attendee::where('event_id', $notification->event->id)->where('is_active', true)->get();
                        if($attendees->isNotEmpty()){
                            foreach($attendees as $attendee){
                                // PUSH NOTIFICATION


                                AttendeeNotification::create([
                                    'event_id' => $notification->event->id,
                                    'attendee_id' => $attendee->id,
                                    'notification_id' => $notification->id,
                                    'sent_datetime' => Carbon::now(),
                                ]);
                            }
                        }

                        // UPDATE NOTIFICATION
                        Notification::where('id', $notification->id)->update([
                            'is_sent' => true,
                        ]);
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
