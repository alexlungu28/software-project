<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RubricEntryController extends Controller
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('rubricEntry_create',['rubrics' => (new RubricController)->getAllRubric()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rubricId = $request->input('rubric_id');
        $distance = $request->input('distance');
        $isRow = $request->input('is_row');
        $description = $request->input('description');

        $data=array("rubric_id"=>$rubricId,"distance"=>$distance,'is_row'=>$isRow, "description" =>$description,'created_at' =>now(), 'updated_at' => now());
        DB::table('rubric_entries')->insert($data);
        echo "Record inserted successfully.<br/>";
        echo '<a href = "/rubricEntryCreate">Click Here</a> to go back.';
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

    public function view($id) {
        $rubric = Rubric::find($id);
        return view('rubric', ['rubric' => $rubric,
            'rubricEntries' => $rubric->rubricEntry,
            'rubricData' => $rubric->rubricData]);
    }
}
