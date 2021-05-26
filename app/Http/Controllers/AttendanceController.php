<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\GroupUser;
use DB;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
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
        // return Attendance::where('user_id', $id)->where('week', $week)->get();
        return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);

        // return $attendance;
    }

    /**
     * Update the the status of the attendance.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Attendance   $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance          = Attendance::find($id);
        $attendance->status = $request->get('update');
        if ($attendance->status == 'Present') {
            $attendance->reason = " ";
            $request->replace(
                ['reason' => '-']
            );
        } else {
            $attendance->reason = $request->get('reason');
        }

        $request->validate(
            ['reason' => 'required']
        );
        $attendance->save();

        return back();
    }


    /**
     * Controller for route with weeks and groups.
     *
     * @param $week
     * @param $group
     * @return Application|Factory|View
     */

    public function weekGroup($group, $week)
    {
        //fetching the editionId as it needs to be passed to the view.
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $group)->get()->first()->course_edition_id;

        //find group of students
        $usersgroup = GroupUser::all()->where('group_id', '=', $group);

        //list of students
        $users = [];
        foreach ($usersgroup as $item) {
            $user1 = User::find($item->user_id);
            array_push($users, $user1);
        }

        //create and add attendance object to database
        //added only if it does not exist for the current group and week
        $attendances = [];
        foreach ($users as $user) {
            if ($user->affiliation === 'student') {
                $id = $user->id;

                if (Attendance::where('user_id', '=', $id)
                        ->where('week', '=', $week)
                        ->where('group_id', '=', $group)
                        ->exists() === false) {
                    $attendance          = new Attendance;
                    $attendance->user_id = $id;
                    $attendance->group_id = $group;
                    $attendance->week    = $week;
                    $attendance->status = null;
                    $attendance->reason  = null;
                    $attendance->save();
                }
                $atts = Attendance::select('*')
                        ->where('group_id', '=', $group)
                        ->where('week', '=', $week)
                        ->where('user_id', '=', $user->id)->get();

                // For sure there is only 1 $att with specific week and user_id, but get() returns a collection.
                foreach ($atts as $att) {
                    $attendance = $att;
                }
                array_push($attendances, $attendance);
            }
        }

        return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);
    }
}
