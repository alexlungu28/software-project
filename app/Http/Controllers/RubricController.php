<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class RubricController extends Controller
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
     * @return Application|Factory|View
     */
    public function create($editionId)
    {
        return view(
            'rubric_create',
            [
                'edition_id' => $editionId,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $courseEdition = $request->input('edition');
        $week = $request->input('week');
        $data = array(
            'name'=>$name,
            'course_edition_id' => $courseEdition,
            'week' => $week,
            'created_at' => now(),
            'updated_at' => now(),
        );
        Rubric::insert($data);
        return redirect('/viewRubrics/' . $courseEdition);
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
     * @return Application|Factory|View
     */
    public function edit()
    {
        return view('rubric_edit', ['rubrics' => Rubric::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $week = $request->input('week');
        $rubric = Rubric::find($id);
        $rubric->name = $name;
        $rubric->week = $week;
        $rubric->save();
        return redirect('/viewRubrics/' . $rubric->course_edition_id);
    }

    /**
     * Shows the form to select the rubric to be deleted
     *
     * @return Application|Factory|View
     */
    public function delete()
    {
        $rubrics = Rubric::all();
        return view('rubric_delete', [
            "rubrics" => $rubrics,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(Request $request)
    {
        $courseEdition = Rubric::find($request->input('id'))->course_edition_id;
        Rubric::destroy($request->input('id'));
        return redirect('/viewRubrics/' . $courseEdition);
    }

    /**
     * Rubric view based on edition id.
     *
     * @param $editionId
     * @return Application|Factory|View
     */
    public function view($editionId)
    {
        $rubrics = Rubric::all()->where('course_edition_id', '=', $editionId);
        return view('allrubrics', [
            "rubrics" => $rubrics,
            "edition_id" => $editionId,
        ]);
    }
}
