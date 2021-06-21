<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\Deadline;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class Notify extends Command
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
    protected $description = 'Sends notifications to lecturers and Head TAs that deadlines
    for interventions are approaching or have already passed';

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
     * Sends an email notification to users.
     * @param $users: the users that will receive the notification - only for passed deadlines
     * @param $mailPassed: the interventions with an expired deadline
     * @param $mailApproaching: the interventions with a deadline coming soon
     */
    private function mail($users, &$mailPassed, $mailApproaching)
    {
        if (!empty($mailPassed)) {
            $users->map(function ($user) use (&$mailPassed) {
                $text = "";
                foreach ($mailPassed as $intervention) {
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
        if (!empty($mailApproaching)) {
            foreach ($mailApproaching as $intervention) {
                $interventionUser = User::find($intervention->user_id);
                $text = "The deadline for your intervention is in "
                    . Carbon::now()->diffInHours($intervention->end_day)
                    . " hours, on " . $intervention->end_day
                    . ".\r\nAction to be taken: " . $intervention->action;
                Mail::raw($text, function ($mail) use ($interventionUser) {
                    $mail->to($interventionUser->email)
                        ->subject('Gradinator: Deadline approaching for individual intervention');
                });
            }
        }
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
        })->unique();
        $mailPassed = array();
        $mailApproaching = array();
        DB::table('course_edition_user')->get()
            ->map(function ($editionUser) use ($users, &$mailPassed, &$mailApproaching) {
                $userInterventions = DB::table('interventions_individual')
                ->where('user_id', '=', $editionUser->user_id)->get();
                $currentDate = Carbon::now();
                $userInterventions
                ->map(function ($intervention) use ($currentDate, $users, &$mailPassed, &$mailApproaching) {
                    if ($currentDate->gt($intervention->end_day)) {
                        $notification = DB::table('notifications')
                        ->where('data', 'like', '%'
                            . 'Deadline passed%'
                            . '"user_id":' . $intervention->user_id
                            . ',"group_id":' . $intervention->group_id
                            . ',"reason":"' . $intervention->reason
                            . '","action":"' . $intervention->action
                            . '","start_day":"' . $intervention->start_day
                            . '","end_day":"' . $intervention->end_day
                            . '%')
                            ->get()->first();
                        if ($notification == null) {
                            Notification::send($users, new Deadline($intervention, 'passed'));
                            if (!in_array($intervention, $mailPassed)) {
                                array_push($mailPassed, $intervention);
                            }
                        }
                    } elseif ($currentDate->lt($intervention->end_day)
                    && $currentDate->diffInHours($intervention->end_day) <= 48) {
                        $notification = DB::table('notifications')
                        ->where('data', 'like', '%'
                            . 'Deadline approaching%'
                            . '"user_id":' . $intervention->user_id
                            . ',"group_id":' . $intervention->group_id
                            . ',"reason":"' . $intervention->reason
                            . '","action":"' . $intervention->action
                            . '","start_day":"' . $intervention->start_day
                            . '","end_day":"' . $intervention->end_day
                            . '%')
                        ->get()->first();
                        if ($notification == null) {
                            $user = User::where('id', '=', $intervention->user_id)->get()->first();
                            Notification::send($user, new Deadline($intervention, 'approaching'));
                            if (!in_array($intervention, $mailApproaching)) {
                                array_push($mailApproaching, $intervention);
                            }
                        }
                    }
                });
            });
        $this->mail($users, $mailPassed, $mailApproaching);
    }
}
