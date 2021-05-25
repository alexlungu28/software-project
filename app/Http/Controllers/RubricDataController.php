<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class RubricDataController extends Controller
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
     * @param $id
     * @return void
     */
    public function store(Request $request, $id)
    {
        foreach (Rubric::find($id)->rubricData as $entry) {
            $value = $request->input("" . $entry->row_number);
            if ($value === null) {
                $value = -1;
            }
            $note = $request->input("text" . $entry->row_number);
            $key = array("rubric_id"=>$id, "row_number" => $entry->row_number);
            $data = array("value" => $value, "note" => $note, 'created_at' => now(), 'updated_at' => now());
            DB::table('rubric_data')->updateOrInsert($key, $data);
        }
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
}
