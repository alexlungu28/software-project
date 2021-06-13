<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\Note;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class InterventionsController extends Controller
{


    /**
     * Show all interventions of current course edition.
     *
     * @param $editionId
     * @return Application|Factory|View
     */
    public function showAllInterventions($editionId)
    {
        $interventions = Intervention::all()->sortBy('end_day')->sortBy('status');
       // return $interventions;
        $groupIds = DB::table('groups')
            ->select('groups.id')
            ->where('groups.course_edition_id', '=', $editionId)
            ->pluck('groups.id');
        $notes = [];
        foreach ($groupIds as $groupId) {
            if (Note::where('problem_signal', '>', 1)->where('group_id', $groupId)->exists()) {
                $notesAux = Note::where('problem_signal', '>', 1)->where('group_id', $groupId)->get();
                foreach ($notesAux as $note) {
                    array_push($notes, $note);
                }
            }
        }

        return view('interventions', [
            "interventions" => $interventions,
            "edition_id" => $editionId,
            "notes" => $notes
        ]);
    }

    public function editIntervention(Request $request, $interventionId)
    {
        $intervention          = Intervention::find($interventionId);

        //return $request->get('editStart1');
        if (preg_match("/^(note)\d+$/i", $intervention->reason) == false) {
            $intervention->reason = $request->get('editReason');
        }

        $intervention->action = $request->get('editAction');
        $intervention->start_day = $request->input('editStart'. $interventionId);
        $intervention->end_day = $request->input('editEnd' . $interventionId);

        $intervention->save();

        return back();
    }

    public function createIntervention(Request $request, $editionId)
    {
        $intervention          = new Intervention();
        $userId = $request->get('createUser');
        $intervention->user_id = $userId;

        $groupId = DB::table('group_user')
                    ->join('course_edition_user', 'group_user.user_id', '=', 'course_edition_user.user_id')
                    ->select('group_user.group_id')
                    ->where('group_user.user_id', '=', $userId)
                    ->where('course_edition_user.course_edition_id', '=', $editionId)
                    ->pluck('group_user.group_id')->first();

        $intervention->group_id = $groupId;

        $intervention->reason = $request->get('createReason');
        $intervention->action = $request->get('createAction');
        $intervention->start_day = $request->input('createStart' . $editionId);
        $intervention->end_day = $request->input('createEnd' . $editionId);
        $intervention->status = 1; //active - unsolved

        $intervention->save();

        return back();
    }



    public function createInterventionNote(Request $request, $noteId)
    {
        $intervention          = new Intervention();
        $note = Note::find($noteId);
        $intervention->user_id = $note->user_id;
        $intervention->group_id = $note->group_id;
        $intervention->reason = "note" . $noteId;

        $intervention->action = $request->get('createAction');
        $intervention->start_day = $request->input('createStartNote' . $noteId);
        $intervention->end_day = $request->input('createEndNote' . $noteId);
        $intervention->status = 1;

        $intervention->save();

        return back();
    }

    public function deleteIntervention(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->delete();
        return back();
    }


    public function statusActive(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 1;
        $intervention->status_note = $request->get('active_note');
        $intervention->save();
        return back();
    }

    public function statusExtend(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 2;
        $intervention->end_day = $request->get('extend_end' . $interventionId);
        $intervention->status_note = $request->get('extend_note');
        $intervention->save();
        return back();
    }

    public function statusUnsolved(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 3;
        $intervention->status_note = $request->get('unsolved_note');
        $intervention->save();
        return back();
    }

    public function statusSolved(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 4;
        $intervention->status_note = $request->get('solved_note');
        $intervention->save();
        return back();
    }
}
