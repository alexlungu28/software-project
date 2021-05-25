<?php

namespace App\Http\Controllers;

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
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index($editionId)
    {
        $users       = DB::select('select * from users');
        $attendances = [];
        foreach ($users as $user) {
            if ($user->affiliation == 'student') {
                $id   = $user->id;
                $week = 1;
                if (Attendance::where('user_id', '=', $id)->where('week', '=', $week)->exists() == false) {
                    $present             = 0;
                    $attendance          = new Attendance;
                    $attendance->user_id = $id;
                    $attendance->week    = $week;
                    $attendance->present = null;
                    $attendance->reason  = 'salut';
                    $attendance->save();
                    array_push($attendances, $attendance);
                }
            }
        }

        // foreach ($attendances as $att) {
        // echo $att;
        // echo "\n";
        // }
        $attendances = Attendance::all();
        // return Attendance::where('user_id', $id)->where('week', $week)->get();
        return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);

        // return $attendance;
    }//end index()


    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create($userId, $week)
    {
    }//end create()


    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
    }//end store()


    /**
     * Display the specified resource.
     *
     * @param Attendance $attendance
     * @return void
     */
    public function show(Attendance $attendance)
    {
    }//end show()


    /**
     * Show the form for editing the specified resource.
     *
     * @param Attendance $attendance
     * @return void
     */
    public function edit(Attendance $attendance)
    {
    }//end edit()


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $attendance          = Attendance::find($id);
        $attendance->present = $request->get('update');
        if ($attendance->present == 'Present') {
            $attendance->reason = '-';
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
    }//end update()


    /**
     * Remove the specified resource from storage.
     *
     * @param Attendance $attendance
     * @return void
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
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $group)->get()->first()->course_edition_id;
        $usersgroup = GroupUser::all()->where('group_id', '=', $group);

        $users = [];
        foreach ($usersgroup as $item) {
            $user1 = User::find($item->user_id);
            array_push($users, $user1);
        }

        $attendances = [];
        foreach ($users as $user) {
            if ($user->affiliation === 'student') {
                $id = $user->id;

                if (Attendance::where('user_id', '=', $id)->where('week', '=', $week)->exists() === false) {
                    $attendance          = new Attendance;
                    $attendance->user_id = $id;
                    $attendance->week    = $week;
                    $attendance->present = null;
                    $attendance->reason  = '';
                    $attendance->save();
                    // array_push($attendances, $attendance);
                }

                $atts = Attendance::select('*')->where('week', '=', $week)->where('user_id', '=', $user->id)->get();

                // For sure there is only 1 $att with specific week and user_id, but get() returns a collection.
                foreach ($atts as $att) {
                    $attendance = $att;
                }

                array_push($attendances, $attendance);
            }//end if
        }//end foreach

        return view('attendance_submit')->with('attendances', $attendances)->with('edition_id', $editionId);
    }//end week_group()
}//end class
