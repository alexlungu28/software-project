<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Rubric;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return void
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return void
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return void
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id)
    {
        //
    }

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
        if ($role === 'lecturer') {
            return view('weeks', ['edition_id' => $editionId, 'group_id' => $id, 'group' => Group::find($id)]);
        } else {
            return view('weeksTA', ['edition_id' => $editionId, 'group_id' => $id]);
        }
    }

    public function viewWeek($id, $week)
    {
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $id)->get()->first()->course_edition_id;
        $rubrics = Rubric::all()->where('course_edition_id', '=', $editionId)->where('week', '=', $week);
        return view('week', ['edition_id' => $editionId, 'group_id' => $id, 'week' => $week, 'rubrics' => $rubrics]);
    }
}
