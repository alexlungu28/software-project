<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
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
