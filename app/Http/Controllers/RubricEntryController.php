<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\Input;

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

    public function autoIncrementDistance($id, $isRow)
    {
        if (RubricEntry::where('rubric_id', '=', $id)->exists()) {
            if (RubricEntry::where('is_row', '=', $isRow)->exists()) {
                $rubricEntrySameId = RubricEntry::where('is_row', '=', $isRow)->where('rubric_id', '=', $id)->get();
                $max = 0;
                foreach ($rubricEntrySameId as $value) {
                    if ($value->distance>$max) {
                        $max=$value->distance;
                    }
                }
                return $max + 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function create()
    {
        return view('rubricEntry_create', ['rubrics' => (new RubricController)->getAllRubric()]);
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
        /*$distance = $request->input('distance');*/
        $isRow = $request->input('is_row');
        $distance = $this->autoIncrementDistance($rubricId, $isRow);
        $description = $request->input('description');

        $data = array("rubric_id"=>$rubricId, "distance" =>$distance, 'is_row'=>$isRow,
            "description" =>$description, 'created_at' =>now(), 'updated_at' => now());
        DB::table('rubric_entries')->insert($data);

        if ($isRow) {
            $rubricData = array("rubric_id" => $rubricId, "row_number" => $distance,
                "value" => "-1", "note" => null,
                "created_at" => now(), "updated_at" => now());
            DB::table('rubric_data')->insertOrIgnore($rubricData);
        }
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function edit($id, $isRow)
    {
        $rubric = Rubric::find($id);
        return view('rubricEntry_update', ['rubric' => $rubric, 'isRow' =>$isRow, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $isRow = $request->input('isRow');
        $distance = $request->input('distance');
        $description = $request->input('description');
        DB::table('rubric_entries')->where('rubric_id', '=', $id)
            ->where('is_row', '=', $isRow)
            ->where('distance', '=', $distance)
            ->update(['description' => $description]);

        echo "Record updated successfully.<br/>";
        echo "<a href = " . "/rubricEntryEdit/" . $id . "/" . $isRow . ">Click Here</a> to go back.";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $distance, $isRow)
    {
        RubricEntry::where('rubric_id', '=', $id)->where('distance', '=', $distance)
            ->where('is_row', '=', $isRow)->delete();
        if ($isRow == 1) {
            RubricData::where('rubric_id', '=', $id)->where('row_number', '=', $distance)->delete();
        }
    }

    public function view($id)
    {
        $rubric = Rubric::find($id);
        $rubricColumnEntries = $rubric->rubricEntry->where('is_row', '=', '0')->sortBy('distance');
        $rubricRowEntries = $rubric->rubricEntry->where('is_row', '=', '1')->sortBy('distance');
        $rubricData = $rubric->rubricData;
        return view('pages.table_list', ['rubric' => $rubric,
            'rubricColumnEntries' => $rubricColumnEntries,
            'rubricRowEntries' => $rubricRowEntries,
            'rubricData' => $rubricData]);
    }
}
