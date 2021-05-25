<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseEditionUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //maybe change the methods so it's more dynamic
    public function setRoleTAFromStudent($id, $editionId)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'TA';
            $student->save();
        }
    }

    public function setRoleHeadTAFromStudent($id, $editionId)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'Head_TA';
            $student->save();
        }
    }

    public function setRoleStudentFromTA($id, $editionId)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'student';
            $student->save();
        }
    }

    public function setRoleStudentFromHeadTA($id, $editionId)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'student';
            $student->save();
        }
    }

    public function view($editionId)
    {
        $allUsers = User::all();
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('role', '=', 'student')
            ->orWhere('role', '=', 'TA')
            ->orWhere('role', '=', 'HeadTA')->get();
        //$netIdOfUser = DB::table('users')->where('id','=','');

        return view('pages.studentList', [
            'allUsers' => $allUsers,
            'courseEditionUser' => $courseEditionUser,
            'edition_id' => $editionId]);
    }

}
