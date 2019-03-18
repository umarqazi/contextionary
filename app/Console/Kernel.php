<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'App\Console\Commands\CheckMeaning',
        'App\Console\Commands\CheckIllustratorBids',
        'App\Console\Commands\CheckTranslations',
        'App\Console\Commands\SubscriptionCheck',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
        $schedule->command('meaning:vote')->twiceDaily(1, 13);
        $schedule->command('illustrator:bids')->twiceDaily(1, 13);
        $schedule->command('translations:bids')->twiceDaily(1, 13);
        $schedule->command('subscription:check')->twiceDaily(1, 13);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
