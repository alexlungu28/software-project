<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RubricDataController extends Controller
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
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $i = 0;
        foreach(Rubric::find($id)->rubricData as $entry) {
            $value = $request->input("" . $i);
            if ($value === null) {
                $value = -1;
            }
            $note = $request->input("text" . $i);
            $key = array("rubric_id"=>$id, "row_number" => $i);
            $data = array("value" => $value, "note" => $note, 'created_at' => now(), 'updated_at' => now());
            DB::table('rubric_data')->updateOrInsert($key, $data);
            $i++;
        }
        echo "Record inserted successfully.<br/>";
        echo '<a href = "/viewRubric/1">Click Here</a> to go back.';
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
}
