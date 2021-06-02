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
            $userToInsert = array("user_id" => $userId, "group_id" => $group);
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
        $employeeUsers = DB::table('users')
            ->where('affiliation', '=', 'employee')->get();
        return view('pages.studentList', [
            'allUsers' => $allUsers,
            'courseEditionUser' => $courseEditionUser,
            'employeeUsers' => $employeeUsers,
            'edition_id' => $editionId]);
    }

    public function insertLecturerFromUsers($courseEdition, $userId)
    {
        if (DB::table('course_edition_user')
            ->where('user_id', '=', $userId)
            ->where('course_edition_id', '=', $courseEdition)
            ->exists()) {
            $updateUser =  array("user_id" => $userId,"course_edition_id" => $courseEdition , "role" => "lecturer");
            DB::table('course_edition_user')
                ->where('user_id', '=', $userId)
                ->where('course_edition_id', '=', $courseEdition)->update($updateUser);
        } else {
            $userToInsert = array("user_id" => $userId,
                "course_edition_id" => $courseEdition , "role" => "lecturer");
            DB::table('course_edition_user')->updateOrInsert($userToInsert);
        }
        return redirect()->back();
    }

    public function insertHeadTAFromUsers($courseEdition, $userId)
    {
        if ($userId) {
            if (DB::table('course_edition_user')
                ->where('user_id', '=', $userId)
                ->where('course_edition_id', '=', $courseEdition)
                ->exists()) {
                $updateUser =  array("user_id" => $userId,"course_edition_id" => $courseEdition , "role" => "HeadTA");
                DB::table('course_edition_user')
                    ->where('user_id', '=', $userId)
                    ->where('course_edition_id', '=', $courseEdition)->update($updateUser);
            } else {
                $userToInsert =  array("user_id" => $userId,"course_edition_id" => $courseEdition , "role" => "HeadTA");
                DB::table('course_edition_user')->updateOrInsert($userToInsert);
            }
        }
        return redirect()->back();
    }
}
