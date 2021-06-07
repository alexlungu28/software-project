<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\GroupUser;
use DB;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    /**
     * Display an overview of attendances for all users of the course edition.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($editionId)
    {
        $users = CourseEditionUser::where('course_edition_id', $editionId)->where('role', 'student')->get(['user_id']);

        $attendances = Attendance::all()->sortBy('week');

        return view('attendance')->with('attendances', $attendances)->with('edition_id', $editionId);

        // return $attendance;
    }

    /**
     * Update the the status of the attendance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance          = Attendance::find($id);

        $attendance->status = $request->get('update');
        $attendance->reason = $request->input('reason');

        $attendance->save();

        return back();
    }


    /**
     * Controller for route with weeks and groups. Creates attendance for entry
     * for all students based on group and week, only if they are not in the
     * database already.
     *
     * @param $week
     * @param $group
     * @return view
     */

    public function weekGroup($group, $week)
    {
        //fetching the editionId as it needs to be passed to the view.
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $group)->get()->first()->course_edition_id;

        //find group of students
        $usersGroup = GroupUser::all()->where('group_id', '=', $group);

        //list of students
        $users = [];

        foreach ($usersGroup as $item) {
            $user = User::find($item->user_id);
            array_push($users, $user);
        }

        //create and add attendance object *for students* to database
        //added only if it does not exist for the current group and week
        $attendances = [];
        foreach ($users as $user) {
            if (CourseEditionUser::where('course_edition_id', '=', $editionId)
                ->where('user_id', '=', $user->id)
                ->where('role', '=', 'student')->exists()) {
                $id = $user->id;

                if (Attendance::where('user_id', '=', $id)
                        ->where('week', '=', $week)
                        ->where('group_id', '=', $group)
                        ->exists() === false) {
                    $this->createAttendance($user, $group, $week);
                }
            }
        }
        $attendances = Attendance::select('*')
            ->where('group_id', '=', $group)
            ->where('week', '=', $week)->get();

        return view('attendance')
            ->with('attendances', $attendances)
            ->with('edition_id', $editionId)
            ->with('week', $week)->with('group_id', $group);
    }

    /**
     * Function that creates a new attendance object and adds it to the database.
     * This function is only called when no entry for a student in a specific week exists.
     * @param $user - user_id
     * @param $group - group_id
     * @param $week - week number
     */
    public function createAttendance($user, $group, $week)
    {
                $id = $user->id;
                $attendance          = new Attendance();
                $attendance->user_id = $id;
                $attendance->group_id = $group;
                $attendance->week    = $week;
                $attendance->status = null;
                $attendance->reason  = null;
                $attendance->save();
    }
}
