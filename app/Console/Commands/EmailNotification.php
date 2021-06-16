<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\DeadlinePassed;
use Carbon\Carbon;
use Illuminate\Console\Command;
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
    protected $signature = 'notify';

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
     * @return void
     */
    public function handle()
    {
        $ceUsers = DB::table('course_edition_user')->where('role', '=', 'lecturer')
            ->orWhere('role', '=', 'HeadTA')
            ->get();
        $users = $ceUsers->map(function ($ceUser) {
            return User::where('id', '=', $ceUser->user_id)->get()->first();
        });
        $mailInterventions = array();
        DB::table('course_edition_user')->get()->map(function ($editionUser) use ($users, &$mailInterventions) {
            $userInterventions = DB::table('interventions_individual')
                ->where('user_id', '=', $editionUser->user_id)->get();
            $currentDate = Carbon::now();
            $userInterventions->map(function ($intervention) use ($currentDate, $users, &$mailInterventions) {
                if ($currentDate->gt($intervention->end_day)) {
                    $notification = DB::table('notifications')
                        ->where('data', 'like', '%' .
                            '"user_id":' . $intervention->user_id
                            . ',"group_id":' . $intervention->group_id
                            . ',"reason":"' . $intervention->reason
                            . '","action":"' . $intervention->action
                            . '","start_day":"' . $intervention->start_day
                            . '","end_day":"' . $intervention->end_day
                            . '%')
                        ->get()->first();
                    if ($notification == null) {
                        Notification::send($users, new DeadlinePassed($intervention));
                        if (!in_array($intervention, $mailInterventions)) {
                            echo 'pushed!';
                            array_push($mailInterventions, $intervention);
                        }
                    }
                }
            });
        });
        if (!empty($mailInterventions)) {
            $users->unique()->map(function ($user) use (&$mailInterventions) {
                $text = "";
                foreach ($mailInterventions as $intervention) {
                    $interventionUser = User::find($intervention->user_id);
                    $text .= "Intervention deadline passed for student " . $interventionUser->first_name . " "
                        . $interventionUser->last_name . ", group " . $intervention->group_id . ". Deadline was on "
                    . $intervention->end_day . "\r\n";
                }
                Mail::raw($text, function ($mail) use ($user) {
                    $mail->to($user->email)->subject('Gradinator: Deadline passed for individual intervention');
                });
            });
        }
    }
}
