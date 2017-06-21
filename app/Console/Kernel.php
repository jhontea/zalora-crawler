<?php

namespace App\Console;

use App\Console\Commands\PriceChangeCommand;
use App\Console\Commands\PriceNowCommand;
use App\Console\Commands\StyleChangeCommand;
use App\Console\Commands\UrlReactiveCommand;
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
        PriceChangeCommand::class,
        PriceNowCommand::class,
        StyleChangeCommand::class,
        UrlReactiveCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    //not yet for implemented, uncomment to implement using scheduled
    /*protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
    }*/

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('price:change')->dailyAt('10:00');
        $schedule->command('url:check')->dailyAt('10:00');
        $schedule->command('price:now')->twiceDaily(9, 10);
        $schedule->command('style:change')->dailyAt('8:00');
    }
}
