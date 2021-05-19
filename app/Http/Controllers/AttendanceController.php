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
    public function index()
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
                    $attendance->present = $present;
                    $attendance->reason = "salut";
                    $attendance->save();
                    array_push($attendances, $attendance);
                }
            }
        }
//            foreach ($attendances as $att) {
//                echo $att;
//                echo "\n";
//            }

        $attendances = Attendance::all();
 //           return Attendance::where('user_id', $id)->where('week', $week)->get();
      return view('attendance_submit')->with('attendances', $attendances);

            //return $attendance;




    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($user_id, $week)
    {
        $attendance = new Attendance;
        $attendance->user_id=$user_id;
        $attendance->week=$week;
        $attendance->present=0;
        $attendance->reason="";
        $attendance->save();

        return $attendance;
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
        $attendance->present=$request->get('update');
        $attendance->reason=$request->get('reason');
        $attendance->save();

        $week = $attendance->week;
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

    public function week($week, $group) {
        $usersgroup = GroupUser::all()->where('group_id', '=', $group);
        //eturn $usersgroup;
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
                    $attendance->present = 0;
                    $attendance->reason = "";
                    $attendance->save();
             //       array_push($attendances, $attendance);
                }
                   // array_push($attendances, Attendance::all() -> where('user_id', '=', $id)->where('week', '=', $week));
            }
        }
        foreach($users as $user) {
            $attendance = Attendance::all() -> where('week', '=', $week) -> where('user_id', '=', $user->id);
            //return $attendance;
            array_push($attendances, $attendance[0]);
        }

       // return $attendances;
       // $attendances = Attendance::all()->where('week', '=', $week);


        return view('attendance_submit')->with('attendances', $attendances);

    }
}
