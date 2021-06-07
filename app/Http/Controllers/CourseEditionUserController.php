<?php

namespace App\Http\Controllers;

use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;
use function Illuminate\Events\queueable;

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
        $groups = CourseEdition::find($editionId)->groups;
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('role', '=', 'TA')
            ->orWhere(function ($query) use ($editionId) {
                $query->where('course_edition_id', '=', $editionId)
                    ->where('role', '=', 'HeadTA');
            })
            ->get();

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
        $userId = $request->input('user_id');
        $editionId = $request->input('edition_id');
        $groupUsers = DB::table('group_user')->where('user_id', '=', $userId)->get();
        foreach ($groupUsers as $groupUser) {
            $group = Group::find($groupUser->group_id);
            if ($group->course_edition_id == $editionId) {
                GroupUser::find($groupUser->id)->delete();
            }
        }
        $groups = $request->input('groups');
        if ($groups == null) {
            return redirect()->back();
        }
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
            ->where('role', '!=', 'lecturer')
            ->get();
        $employeeUsers = DB::table('users')
            ->where('affiliation', '=', 'employee')->get();
        return view('pages.studentList', [
            'allUsers' => $allUsers,
            'courseEditionUser' => $courseEditionUser,
            'employeeUsers' => $employeeUsers,
            'edition_id' => $editionId]);
    }

    /**
     * This method either inserts the user in the database or it modifies the role to lecturer.
     * @param $courseEdition
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * This method either inserts the user in the database or it modifies the role to HeadTA.
     * @param $courseEdition
     * @param $userId
     * @return \Illuminate\Http\RedirectResponse
     */
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
