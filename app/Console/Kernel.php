<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
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

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
//        $schedule->call(function () {
//            DB::table('recent_users')->delete();
//        })->daily();
        //$schedule->command(__DIR__.'/Commands/EmailNotification')->everyMinute();
//        $schedule->call(function () {
//            Mail::raw("test2", function ($mail) {
//                $mail->to('test@gmail.com')->subject('subject2');
//            });
//        })->everyMinute();
    }
}
