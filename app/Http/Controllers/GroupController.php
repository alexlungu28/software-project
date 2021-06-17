<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Rubric;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Return the group view based on the group id.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function view($id)
    {
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $id)->get()->first()->course_edition_id;
        $role = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', Auth::id())->get()->first()->role;
        $usersFromGroup = DB::table('group_user')->select('user_id')->where('group_id', '=', $id)->get();
        $gitanalyses = DB::table('gitanalyses')->where('group_id', '=', $id)->get();
        if ($role === 'lecturer') {
            return view('weeks', ['edition_id' => $editionId, 'group_id' => $id,
                'group' => Group::find($id), 'users' => $usersFromGroup, 'gitanalyses' => $gitanalyses]);
        } else {
            return view('weeks', ['edition_id' => $editionId, 'group_id' => $id, 'group' => Group::find($id),
                'users' => $usersFromGroup, 'gitanalyses' => $gitanalyses]);
        }
    }

    public function viewWeek($id, $week)
    {
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $id)->get()->first()->course_edition_id;
        $courseRubrics = Rubric::all()->where('course_edition_id', '=', $editionId);
        $rubrics = $courseRubrics->where('week', '=', $week)->merge($courseRubrics->where('week', '=', null));
        $role = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', Auth::id())->get()->first()->role;
        $gitanalyses = DB::table('gitanalyses')->where('group_id', '=', $id)->where('week_number', '=', $week)->get();
        if ($role === 'lecturer') {
            return view('week', ['edition_id' => $editionId, 'group_id' => $id,
                'week' => $week, 'rubrics' => $rubrics, 'gitanalyses' => $gitanalyses]);
        } else {
            return view('weekTA', ['edition_id' => $editionId, 'group_id' => $id,
                'week' => $week, 'rubrics' => $rubrics, 'gitanalyses' => $gitanalyses]);
        }
    }
}
