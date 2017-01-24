<?php namespace App\Console;

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
        'App\Console\Commands\Inspire',
        'App\Console\Commands\BillingRun',
        'App\Console\Commands\RecordCustomVM',
        'App\Console\Commands\ProcessNotifications',
        'App\Console\Commands\Firewall\AddRuleCommand',
        'App\Console\Commands\Firewall\FlushCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('billing:run')->dailyAt('07:00');
        $schedule->command('ans:notify')->everyMinute();
    }
}
