<?php

namespace App\Http\Controllers;

use App\Models\GroupUser;
use DB;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($editionId)
    {
        $users       = DB::select('select * from users');

        $attendances = Attendance::all()->sortBy('week');
        // return Attendance::where('user_id', $id)->where('week', $week)->get();
        return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);

        // return $attendance;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userId, $week)
    {
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
    }//end edit()


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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
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
