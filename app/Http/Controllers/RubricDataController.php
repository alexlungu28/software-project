<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request, $id)
    {
        $groupId = $request->input("groupId");
        foreach (Rubric::find($id)->rubricEntry->where('is_row', '=', 1) as $entry) {
            $value = $request->input("" . $entry->distance);
            if ($value == null) {
                $value = -1;
            }
            $note = $request->input("text" . $entry->distance);
            $key = array(
                "rubric_id" => $id,
                "group_id" => $groupId,
                "row_number" => $entry->distance);
            $data = array(
                'value' => $value,
                'note' => $note,
                'user_id' => Auth::id(),
                'created_at' => now(),
                'updated_at' => now());
            DB::table('rubric_data')->updateOrInsert($key, $data);
        }
        return redirect(route('rubric', [$id, $groupId]));
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
