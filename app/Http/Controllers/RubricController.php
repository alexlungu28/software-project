<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RubricController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('rubric_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function store(Request $request)
    {
        $name = $request->input('name');
        $course_edition = '1';
        $data=array('name'=>$name,'course_edition_id' => $course_edition,'created_at' =>now(), 'updated_at' => now());
        DB::table('rubrics')->insert($data);
        echo "Record inserted successfully.<br/>";
        echo '<a href = "/rubricCreate">Click Here</a> to go back.';
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit()
    {
        return view('rubric_edit', ['rubrics' => Rubric::all()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $rubric = Rubric::find($id);
        $rubric->name = $name;
        $rubric->save();

        echo "Record updated successfully.<br/>";
        echo '<a href = "/rubricEdit">Click Here</a> to go back.';
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

    public function view()
    {
        $rubrics = Rubric::all();
        return view('allrubrics', [
            "rubrics" => $rubrics,
        ]);
    }
}
