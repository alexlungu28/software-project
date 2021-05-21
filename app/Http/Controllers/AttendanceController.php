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

        $users = DB::select('select * from users');
        $attendances = [];
        foreach($users as $user) {
            if($user->affiliation == 'student') {
                $id = $user->id;
                $week = 1;
                if (Attendance::where('user_id', '=', $id)->where('week', '=', $week)->exists() == false) {
                    $present = 0;
                    $attendance = new Attendance;
                    $attendance->user_id = $id;
                    $attendance->week = $week;
                    $attendance->present = null;
                    $attendance->reason = "salut";
                    $attendance->save();
                    array_push($attendances, $attendance);
                }
            }
        }
////            foreach ($attendances as $att) {
////                echo $att;
////                echo "\n";
////            }

        $attendances = Attendance::all();
 //           return Attendance::where('user_id', $id)->where('week', $week)->get();
      return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);

            //return $attendance;




    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id, $week)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function show(Attendance $attendance)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function edit(Attendance $attendance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);
        $attendance->present = $request->get('update');
        if ($attendance->present == "Present") {
            $attendance->reason = "-";
        $request->replace([
            'reason' => "-",
        ]);
    }
        else
            $attendance->reason=$request->get('reason');
        $request->validate([
            'reason' => 'required',
        ]);
        $attendance->save();



        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Attendance  $attendance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Attendance $attendance)
    {
        //
    }

    public function week_group($week, $group) {
        $usersgroup = GroupUser::all()->where('group_id', '=', $group);

        $users = [];
        foreach ($usersgroup as $item) {
            $user1 = User::find($item->user_id);
            array_push($users, $user1);
        }
       // $users = DB::select('select * from users');
        $attendances = [];
        foreach($users as $user) {
            if($user->affiliation == 'student') {
                $id = $user->id;

                if (Attendance::where('user_id', '=', $id)->where('week', '=', $week)->exists() == false) {
                    $attendance = new Attendance;
                    $attendance->user_id = $id;
                    $attendance->week = $week;
                    $attendance->present = null;
                    $attendance->reason = "";
                    $attendance->save();
             //       array_push($attendances, $attendance);
                }
                $atts = Attendance::select('*') -> where('week', '=', $week) -> where('user_id', '=', $user->id)->get();

                //for sure there is only 1 $att with specific week and user_id, but get() returns a collection.
                foreach ($atts as $att) {
                    $attendance = $att;
                }

                    array_push($attendances, $attendance);
            }
        }
        return view('attendance_submit')->with('attendances', $attendances);

    }
}
