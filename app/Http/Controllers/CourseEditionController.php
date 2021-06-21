<?php

namespace App\Http\Controllers;

use App\Models\CourseEdition;
use App\Models\Group;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseEditionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param $courseId
     * @return Application|RedirectResponse|Redirector|void
     */
    public function store(Request $request, $courseId)
    {
        try {
            //adding the course edition inside the course editions table
            $year = $request->input('year');
            DB::table('course_editions')->insert(array('course_id'=>$courseId,
                'year'=>$year, 'created_at'=>now(),'updated_at'=>now()));
            $courseEditionId = DB::table('course_editions')->get()->last()->id;
            DB::table('course_edition_user')->insert(array('user_id'=>Auth::id(),
                'course_edition_id'=>$courseEditionId, 'role'=>'lecturer' ,'created_at'=>now(),'updated_at'=>now()));
            return redirect('/courses/' . $courseId);
        } catch (QueryException $e) {
            echo "Course edition already exists.<br/>";
            echo "Redirecting you back to main page...";
            return header("refresh:3;url=/courses/" . $courseId);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse|void
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $year = $request->input('year');
        $courseEdition = CourseEdition::find($id);
        try {
            $courseEdition->year = $year;
            $courseEdition->save();
            return redirect('/courses/' . $courseEdition->course_id);
        } catch (QueryException $e) {
            echo "Course edition already exists.<br/>";
            echo "Redirecting you back to main page...";
            return header("refresh:3;url=/courses/" . $courseEdition->course_id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(Request $request)
    {
        $id = $request->input('id');
        $hardDelete = $request->input('hardDelete');
        $courseId = CourseEdition::find($id)->course_id;
        if (!empty($hardDelete)) {
            CourseEdition::find($id)->forceDelete();
        } else {
            CourseEdition::destroy($id);
        }
        return redirect('/courses/' . $courseId);
    }

    /**
     * Restore the specified course edition.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function restore(Request $request)
    {
        $id = $request->input('id');
        $edition = CourseEdition::withTrashed()->find($id);
        $edition->restore();
        $courseId = CourseEdition::find($id)->course_id;
        return redirect('/courses/' . $courseId);
    }

    public function viewTA($editionId)
    {
        $groups = User::find(Auth::id())->groups()->where('course_edition_id', '=', $editionId)->get();
        $courseId = CourseEdition::find($editionId)->course_id;
        return view('groups.groupTA', [
            "edition_id" => $editionId,
            "groups" => $groups,
            "course_id" => $courseId
        ]);
    }

    public function viewLecturer($editionId)
    {
        $groups = Group::where('course_edition_id', '=', $editionId)->get();
        $courseId = CourseEdition::find($editionId)->course_id;
        $courseEdition = CourseEdition::find($editionId);
        $teachingAssistants = null;
        if ($courseEdition != null) {
            $teachingAssistants = $courseEdition->teachingAssistants;
        }
        return view('groups.allgroups', [
            "edition_id" => $editionId,
            "groups" => $groups,
            "course_id" => $courseId,
            "teachingAssistants" => $teachingAssistants,
        ]);
    }

    /**
     * Returns the course edition view depending on its id.
     *
     * @param $editionId
     * @return Application|Factory|View
     */
    public function view($editionId)
    {
        $role = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', Auth::id())->get()->first()->role;
        if ($role === 'lecturer') {
            return $this->viewLecturer($editionId);
        } elseif ($role === 'TA' || $role === 'HeadTA') {
            return $this->viewTA($editionId);
        } else {
            return redirect('unauthorized');
        }
    }
}
