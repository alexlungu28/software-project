<?php

namespace App\Http\Controllers;

use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\HigherOrderBuilderProxy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class RubricEntryController extends Controller
{

    /**
     * Takes the largest distance in the database for a row or column and increments that by one.
     *
     * @param $id
     * @param $isRow
     * @return HigherOrderBuilderProxy|int|mixed
     */
    public function autoIncrementDistance($id, $isRow)
    {
        if (RubricEntry::withTrashed()->where('rubric_id', '=', $id)->exists()) {
            if (RubricEntry::withTrashed()->where('is_row', '=', $isRow)->exists()) {
                $rubricEntrySameId = RubricEntry::withTrashed()
                    ->where('is_row', '=', $isRow)->where('rubric_id', '=', $id)->get();
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
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('rubricEntry_create', ['rubrics' => Rubric::all()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
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
        $courseEdition = Rubric::find($rubricId)->course_edition_id;
        return redirect('viewRubricTeacher/' . $rubricId . '/' . $courseEdition);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return Application|Factory|View
     */
    public function edit($id, $isRow)
    {
        $rubric = Rubric::find($id);
        return view('rubricEntry_update', ['rubric' => $rubric, 'isRow' =>$isRow, 'id' => $id]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
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

        $courseEdition = Rubric::find($id)->course_edition_id;
        return redirect('viewRubricTeacher/' . $id . '/' . $courseEdition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return void
     */
    public function destroy($id, $distance, $isRow)
    {
        RubricEntry::where('rubric_id', '=', $id)->where('distance', '=', $distance)
            ->where('is_row', '=', $isRow)->delete();
        if ($isRow == 1) {
            RubricData::where('rubric_id', '=', $id)->where('row_number', '=', $distance)->delete();
        }
        $courseEdition = Rubric::find($id)->course_edition_id;
        return redirect('viewRubricTeacher/' . $id . '/' . $courseEdition);
    }

    /**
     * Returns the rubric view for a teacher.
     *
     * @param $id
     * @param $editionId
     * @return Application|Factory|View
     */
    public function teacherview($id, $editionId)
    {

        $rubric = Rubric::find($id);
        $rubricColumnEntries = $rubric->rubricEntry->where('is_row', '=', '0')->sortBy('distance');
        $rubricRowEntries = $rubric->rubricEntry->where('is_row', '=', '1')->sortBy('distance');
        $rubricData = $rubric->rubricData;
        return view('pages.rubricViewTeacher', ['rubric' => $rubric,
            'rubricColumnEntries' => $rubricColumnEntries,
            'rubricRowEntries' => $rubricRowEntries,
            'rubricData' => $rubricData,
            'edition_id' => $editionId]);
    }

    /**
     * Returns the rubric view for a TA.
     *
     * @param $id
     * @param $editionId
     * @return Application|Factory|View
     */
    public function view($id, $editionId)
    {
        $rubric = Rubric::find($id);
        $rubricColumnEntries = $rubric->rubricEntry->where('is_row', '=', '0')->sortBy('distance');
        $rubricRowEntries = $rubric->rubricEntry->where('is_row', '=', '1')->sortBy('distance');
        $rubricData = $rubric->rubricData;
        return view('pages.rubricViewTA', ['rubric' => $rubric,
            'rubricColumnEntries' => $rubricColumnEntries,
            'rubricRowEntries' => $rubricRowEntries,
            'rubricData' => $rubricData,
            'edition_id' => $editionId]);
    }
}
