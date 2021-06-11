<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DeadlinePassed;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class EmailNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send an email notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Mail::raw("test", function ($mail) {
            $mail->to('test@gmail.com')->subject('subject');
        });

        $ceUsers = DB::table('course_edition_user')->where('role', '=', 'lecturer')
            ->orWhere('role', '=', 'HeadTA')
            ->get();
        //ddd($lecturers);
        $users = $ceUsers->map(function ($ceUser) {
            return User::where('id', '=', $ceUser->user_id)->get()->first();
        });
        //ddd($users);
        $interventions = DB::table('course_edition_user')->where('course_edition_id', '=', 1)->get()
            ->flatMap(function ($editionUser) {
                $userInterventions = DB::table('interventions_individual')
                    ->where('user_id', '=', $editionUser->user_id)->get();
                $currentDate = Carbon::now();
                $passed = $userInterventions->map(function ($intervention) use ($currentDate) {
                    if ($currentDate->gt($intervention->end_day)) {
                        return $intervention;
                    }
                    return null;
                })->filter(function ($intervention) {
                    return $intervention != null;
                })->unique();
                return $passed;
            })->filter(function ($intervention) use ($users) {
                if ($intervention != null) {
                    Notification::send($users, new DeadlinePassed($intervention));
                }
            })->unique();
        //Notification::send($users, new DeadlinePassed($intervention));

    }
}
