<?php

namespace App\Console\Commands;

use App\Models\CourseEdition;
use App\Models\Group;
use App\Models\GroupUser;
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
     * Sends an email notification to users regarding individual interventions.
     * @param $users: the users that will receive the notification - only for passed deadlines
     * @param $mailPassed: the interventions with an expired deadline
     * @param $mailApproaching: the interventions with a deadline coming soon
     */
    private function mailStudent($users, &$mailPassed, $mailApproaching)
    {
        if (!empty($mailPassed)) {
            $users->map(function ($user) use (&$mailPassed) {
                $text = "";
                foreach ($mailPassed as $intervention) {
                    $interventionUser = User::find($intervention->user_id);
                    $groupName = Group::find($intervention->group_id)->group_name;
                    $text .= "Intervention deadline passed for student " . $interventionUser->first_name . " "
                        . $interventionUser->last_name . ", " . $groupName . ". Deadline was on "
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
                        ->subject('Deadline approaching for individual intervention');
                });
            }
        }
    }

    /**
     * Sends an email notification to users regarding group interventions.
     * @param $users: the users that will receive the notification - only for passed deadlines
     * @param $mailPassed: the interventions with an expired deadline
     * @param $mailApproaching: the interventions with a deadline coming soon
     */
    private function mailGroup($users, &$mailPassed, $mailApproaching)
    {
        if (!empty($mailPassed)) {
            $users->map(function ($user) use (&$mailPassed) {
                $text = "";
                foreach ($mailPassed as $intervention) {
                    $groupName = Group::find($intervention->group_id)->group_name;
                    $text .= "Intervention deadline passed for ". $groupName . ". Deadline was on "
                        . $intervention->end_day . "\r\n";
                }
                Mail::raw($text, function ($mail) use ($user) {
                    $mail->to($user->email)->subject('Gradinator: Deadline passed for group intervention');
                });
            });
        }
        if (!empty($mailApproaching)) {
            foreach ($mailApproaching as $intervention) {
                $text = "The deadline for your group's intervention is in "
                    . Carbon::now()->diffInHours($intervention->end_day)
                    . " hours, on " . $intervention->end_day
                    . ".\r\nAction to be taken: " . $intervention->action;
                GroupUser::where('group_id', '=', $intervention->group_id)->get()
                    ->map(function ($groupUser) use ($text) {
                        $user = User::find($groupUser->user_id);
                        Mail::raw($text, function ($mail) use ($user) {
                            $mail->to($user->email)
                                ->subject('Deadline approaching for group intervention');
                        });
                    });
            }
        }
    }

    /**
     * Saves notifications related to individual interventions in the database.
     * @param $users: the users that will receive the notification
     * @param $mailPassed: the interventions with an expired deadline
     * @param $mailApproaching: the interventions with a deadline coming soon
     */
    private function studentDeadlines($users, &$mailPassed, &$mailApproaching)
    {
        DB::table('course_edition_user')->get()
            ->map(function ($editionUser) use ($users, &$mailPassed, &$mailApproaching) {
                $userInterventions = DB::table('interventions_individual')
                    ->where('user_id', '=', $editionUser->user_id)->get();
                $currentDate = Carbon::now();
                $userInterventions
                    ->map(function ($intervention) use ($currentDate, $users, &$mailPassed, &$mailApproaching) {
                        // Send notifications only for interventions that are active or extended
                        if (in_array($intervention->status, [1, 2])) {
                            if ($currentDate->gt($intervention->end_day)) {
                                $notification = DB::table('notifications')
                                    ->where('data', 'like', '%'
                                        . 'Deadline passed"%'
                                        . '"user_id":' . $intervention->user_id
                                        . ',"group_id":' . $intervention->group_id
                                        . ',"reason":%' . $intervention->reason
                                        . '%,"action":%' . $intervention->action
                                        . '%,"start_day":"' . $intervention->start_day
                                        . '","end_day":"' . $intervention->end_day
                                        . '","status":' . $intervention->status
                                        . ',"status_note":%' . $intervention->status_note
                                        . '%,"visible_ta":' . $intervention->visible_ta
                                        . '%')
                                    ->get()->first();
                                if ($notification == null) {
                                    Notification::send($users, new Deadline($intervention, 'passed'));
                                    GroupUser::where('group_id', '=', $intervention->group_id)->get()
                                        ->map(function ($groupUser) use ($intervention) {
                                            $user = User::find($groupUser->user_id);
                                            $group = Group::find($groupUser->group_id);
                                            $courseEdition = CourseEdition::find($group->course_edition_id);
                                            $ceUser = DB::table('course_edition_user')
                                                ->where('user_id', '=', $user->id)
                                                ->where('course_edition_id', '=', $courseEdition->id)
                                                ->get()->first();
                                            if ($ceUser->role == 'TA') {
                                                Notification::send($user, new Deadline($intervention, 'passed'));
                                            }
                                        });
                                    if (!in_array($intervention, $mailPassed)) {
                                        array_push($mailPassed, $intervention);
                                    }
                                }
                            } elseif ($currentDate->lt($intervention->end_day)
                                && $currentDate->diffInHours($intervention->end_day) <= 48) {
                                $notification = DB::table('notifications')
                                    ->where('data', 'like', '%'
                                        . 'Deadline approaching"%'
                                        . '"user_id":' . $intervention->user_id
                                        . ',"group_id":' . $intervention->group_id
                                        . ',"reason":%' . $intervention->reason
                                        . '%,"action":%' . $intervention->action
                                        . '%,"start_day":"' . $intervention->start_day
                                        . '","end_day":"' . $intervention->end_day
                                        . '%')
                                    ->get()->first();
                                if ($notification == null) {
                                    $user = User::find($intervention->user_id);
                                    $group = Group::find($intervention->group_id);
                                    $courseEdition = CourseEdition::find($group->course_edition_id);
                                    $ceUser = DB::table('course_edition_user')
                                        ->where('user_id', '=', $user->id)
                                        ->where('course_edition_id', '=', $courseEdition->id)
                                        ->get()->first();
                                    if ($ceUser->role == 'student') {
                                        Notification::send($user, new Deadline($intervention, 'approaching'));
                                    }
                                    if (!in_array($intervention, $mailApproaching)) {
                                        array_push($mailApproaching, $intervention);
                                    }
                                }
                            }
                        }
                    });
            });
    }

    /**
     * Saves notifications related to group interventions in the database.
     * @param $users: the users that will receive the notification
     * @param $mailPassed: the interventions with an expired deadline
     * @param $mailApproaching: the interventions with a deadline coming soon
     */
    private function groupDeadlines($users, &$mailPassed, &$mailApproaching)
    {
        $currentDate = Carbon::now();
        DB::table('interventions_group')->get()
            ->map(function ($intervention) use ($currentDate, $users, &$mailPassed, &$mailApproaching) {
                // Send notifications only for interventions that are active or extended
                if (in_array($intervention->status, [1, 2])) {
                    if ($currentDate->gt($intervention->end_day)) {
                        $notification = DB::table('notifications')
                            ->where('data', 'like', '%'
                                . 'Deadline passed group"%'
                                . ',"group_id":' . $intervention->group_id
                                . ',"reason":%' . $intervention->reason
                                . '%,"action":%' . $intervention->action
                                . '%,"start_day":"' . $intervention->start_day
                                . '","end_day":"' . $intervention->end_day
                                . '","status":' . $intervention->status
                                . ',"status_note":%' . $intervention->status_note
                                . '%,"visible_ta":' . $intervention->visible_ta
                                . '%')
                            ->get();
                        if ($notification == null) {
                            Notification::send($users, new Deadline($intervention, 'passed group'));
                            GroupUser::where('group_id', '=', $intervention->group_id)->get()
                                ->map(function ($groupUser) use ($intervention) {
                                    $user = User::find($groupUser->user_id);
                                    $group = Group::find($groupUser->group_id);
                                    $courseEdition = CourseEdition::find($group->course_edition_id);
                                    $ceUser = DB::table('course_edition_user')
                                        ->where('user_id', '=', $user->id)
                                        ->where('course_edition_id', '=', $courseEdition->id)
                                        ->get()->first();
                                    if ($ceUser->role == 'TA') {
                                        Notification::send($user, new Deadline($intervention, 'passed group'));
                                    }
                                });
                            if (!in_array($intervention, $mailPassed)) {
                                array_push($mailPassed, $intervention);
                            }
                        }
                    } elseif ($currentDate->lt($intervention->end_day)
                        && $currentDate->diffInHours($intervention->end_day) <= 48) {
                        $notification = DB::table('notifications')
                            ->where('data', 'like', '%'
                                . 'Deadline approaching group"%'
                                . ',"group_id":' . $intervention->group_id
                                . ',"reason":%' . $intervention->reason
                                . '%,"action":%' . $intervention->action
                                . '%,"start_day":"' . $intervention->start_day
                                . '","end_day":"' . $intervention->end_day
                                . '%')
                            ->get()->first();
                        if ($notification == null) {
                            GroupUser::where('group_id', '=', $intervention->group_id)->get()
                                ->map(function ($groupUser) use ($intervention) {
                                    $user = User::find($groupUser->user_id);
                                    $group = Group::find($intervention->group_id);
                                    $courseEdition = CourseEdition::find($group->course_edition_id);
                                    $ceUser = DB::table('course_edition_user')
                                        ->where('user_id', '=', $user->id)
                                        ->where('course_edition_id', '=', $courseEdition->id)
                                        ->get()->first();
                                    if ($ceUser->role == 'student') {
                                        Notification::send($user, new Deadline($intervention, 'approaching group'));
                                    }
                                });
                            if (!in_array($intervention, $mailApproaching)) {
                                array_push($mailApproaching, $intervention);
                            }
                        }
                    }
                }
            });
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
        $mailPassedGroup = array();
        $mailApproachingGroup = array();
        $this->studentDeadlines($users, $mailPassed, $mailApproaching);
        $this->groupDeadlines($users, $mailPassedGroup, $mailApproachingGroup);
        $this->mailStudent($users, $mailPassed, $mailApproaching);
        $this->mailGroup($users, $mailPassedGroup, $mailApproachingGroup);
    }
}
