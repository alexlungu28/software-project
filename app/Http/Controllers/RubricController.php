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
    public function create()
    {
        return view('rubric_create');
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
        $courseEdition = '1';
        $data=array('name'=>$name,'course_edition_id' => $courseEdition,'created_at' =>now(), 'updated_at' => now());
        DB::table('rubrics')->insert($data);
        return redirect('/rubricCreate');
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
        $rubric = Rubric::find($id);
        $rubric->name = $name;
        $rubric->save();

        return redirect('/rubricEdit');
    }

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
     * @return void
     */
    public function destroy(Request $request)
    {
        Rubric::destroy($request->input('id'));
        echo "Record deleted successfully.<br/>";
        echo '<a href = "/viewRubrics">Click Here</a> to go back.';
    }

    public function view($editionId)
    {
        $rubrics = Rubric::all()->where('course_edition_id', '=', $editionId);
        return view('allrubrics', [
            "rubrics" => $rubrics,
            "edition_id" => $editionId,
        ]);
    }
}
