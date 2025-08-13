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
        $schedule->command('meetings:check-expired')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('notifications:send-scheduled')->everyMinute()->withoutOverlapping();
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

    // protected $commands = [
    //     \App\Console\Commands\CheckExpiredMeetings::class,
    //     \App\Console\Commands\SendScheduledPushNotifications::class,
    // ];
}
