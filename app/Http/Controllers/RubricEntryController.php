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
        $courseEdition = Rubric::find($rubricId)->course_edition_id;
        return redirect('viewRubricTeacher/' . $rubricId . '/' . $courseEdition);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  $id
     * @return Application|Factory|View
     */
    public function edit($id)
    {
        $rubricEntry = RubricEntry::find($id);
        return view(
            'rubricEntry_update',
            [
                'rubricEntry' => $rubricEntry
            ]
        );
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
        $description = $request->input('description');
        $entry = RubricEntry::find($id);
        $entry->description = $description;
        $entry->save();

        $courseEdition = $entry->rubric->course_edition_id;
        return redirect('viewRubricTeacher/' . $entry->rubric->id . '/' . $courseEdition);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy($id)
    {
        $entry = RubricEntry::find($id);
        $courseEdition = $entry->rubric->course_edition_id;
        $rubricId = $entry->rubric->id;
        if ($entry->is_row == 1) {
            RubricData::where('rubric_id', '=', $rubricId)
                ->where('row_number', '=', $entry->distance)
                ->delete();
        }
        $entry->delete();
        return redirect('viewRubricTeacher/' . $rubricId . '/' . $courseEdition);
    }

    /**
     * Returns the specified resource into storage.
     *
     * @param Request $request
     * @return
     */
    public function rollback(Request $request)
    {
        $id = $request->input('id');
        $entry = RubricEntry::withTrashed()->find($id);
        $entry->restore();
        if ($entry->is_row = 1) {
            RubricData::where('rubric_id', '=', $entry->rubric->id)
                ->where('row_number', '=', $entry->distance)
                ->restore();
        }
        return redirect('viewRubricTeacher/' . $entry->rubric->id . '/' . $entry->rubric->course_edition_id);
    }

    /**
     * Returns the rubric view for a teacher.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function teacherview($id)
    {

        $rubric = Rubric::find($id);
        $rubricColumnEntries = $rubric->rubricEntry->where('is_row', '=', '0')->sortBy('distance');
        $rubricRowEntries = $rubric->rubricEntry->where('is_row', '=', '1')->sortBy('distance');
        $deletedEntries = RubricEntry::onlyTrashed()->where('rubric_id', '=', $id)->get();
        $rubricData = $rubric->rubricData;
        $editionId = $rubric->course_edition_id;
        return view('pages.rubricViewTeacher', ['rubric' => $rubric,
            'rubricColumnEntries' => $rubricColumnEntries,
            'rubricRowEntries' => $rubricRowEntries,
            'rubricData' => $rubricData,
            'deletedEntries' => $deletedEntries,
            'edition_id' => $editionId]);
    }

    /**
     * Returns the rubric view for a TA.
     *
     * @param $id
     * @param $groupId
     * @return Application|Factory|View
     */
    public function view($id, $groupId)
    {
        $rubric = Rubric::find($id);
        $rubricColumnEntries = $rubric->rubricEntry->where('is_row', '=', '0')->sortBy('distance');
        $rubricRowEntries = $rubric->rubricEntry->where('is_row', '=', '1')->sortBy('distance');
        $rubricData = $rubric->rubricData->where('group_id', '=', $groupId);
        return view(
            'pages.rubricViewTA',
            [
                'rubric' => $rubric,
                'rubricColumnEntries' => $rubricColumnEntries,
                'rubricRowEntries' => $rubricRowEntries,
                'rubricData' => $rubricData,
                'edition_id' => $rubric->course_edition_id,
                'group_id' => $groupId,
            ]
        );
    }
}
