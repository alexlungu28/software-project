<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\Group;
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

    /**
     * Sets the role to be a TA from anything that was before.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRoleTA($id)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'TA';
            $student->save();
        }
        return redirect()->back();
    }

    /**
     * Sets the role to be a HeadTA from anything that was before.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRoleHeadTA($id)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'HeadTA';
            $student->save();
        }
        return redirect()->back();
    }

    /**
     * Sets the role to be a Student from anything that was before.
     *
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function setRoleStudent($id)
    {
        $student = CourseEditionUser::find($id);
        if ($student) {
            $student->role = 'student';
            $student->save();
        }
        return redirect()->back();
    }

    /**
     * Method to return the assigntatogroupsview.
     * @param $editionId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function assignTaToGroupsView($editionId)
    {
        $allUsers = User::all();
        $groups = Group::all();
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('role', '=', 'TA')
            ->orWhere('role', '=', 'HeadTA')->get();

        return view('pages.assignTAToGroups', [
            'allUsers' => $allUsers,
            'groups' => $groups,
            'courseEditionUser' => $courseEditionUser,
            'edition_id' => $editionId]);
    }

    /**
     * Method that takes the request and puts the TAs from the request in the group_user table.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function assignTaToGroupsStore(Request $request)
    {
        $groups = $request->input('groups');
        $userId = $request->input('user_id');
        foreach ($groups as $group) {
            $userToInsert = array("user_id" => $userId, "group_id" => $group,
                'created_at' => now(), 'updated_at' => now());
            DB::table('group_user')->updateOrInsert($userToInsert);
        }
        return redirect()->back();
    }


    /**
     * CourseEditionUser view based on edition id.
     *
     * @param $editionId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function view($editionId)
    {
        $allUsers = User::all();
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('role', '=', 'student')
            ->orWhere('role', '=', 'TA')
            ->orWhere('role', '=', 'HeadTA')->get();

        return view('pages.studentList', [
            'allUsers' => $allUsers,
            'courseEditionUser' => $courseEditionUser,
            'edition_id' => $editionId]);
    }
}
