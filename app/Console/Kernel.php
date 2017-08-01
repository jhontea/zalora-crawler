<?php

namespace App\Console;

use App\Console\Commands\EmailTestCommand;
use App\Console\Commands\PriceChangeCommand;
use App\Console\Commands\PriceNowCommand;
use App\Console\Commands\ProductCrawlCommand;
use App\Console\Commands\ProductNewCommand;
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
        EmailTestCommand::class,
        PriceChangeCommand::class,
        PriceNowCommand::class,
        ProductCrawlCommand::class,
        ProductNewCommand::class,
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
        $schedule->command('price:change')->dailyAt('9:00');
        $schedule->command('url:check')->dailyAt('9:00');
        $schedule->command('price:now')->twiceDaily(8, 9);
        $schedule->command('style:change')->dailyAt('7:00');
        $schedule->command('product:new')->dailyAt('6:00');
        $schedule->command('product:crawl')->dailyAt('6:00');
        foreach (['06:00', '09:30', '10:00', '17:00'] as $time) {
            $schedule->command('cache:clear')->dailyAt($time);
        }
        /*$schedule->command('email:test')->everyFiveMinutes();*/
    }
}
