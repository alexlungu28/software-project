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


        //fetching the notes and interventions of the current course edition.
        //first, the groupIds of the current course edition are collected,
        // then the notes and interventions are directly selected from the database and passed to the view.
        $groupIds = DB::table('groups')
            ->select('groups.id')
            ->where('groups.course_edition_id', '=', $editionId)
            ->pluck('groups.id');
        $notes = [];
        $interventions = [];
        foreach ($groupIds as $groupId) {
            if (Note::where('problem_signal', '>', 1)->where('group_id', $groupId)->exists()) {
                $notesAux = Note::where('problem_signal', '>', 1)->where('group_id', $groupId)->get();
                foreach ($notesAux as $noteGroup) {
                    array_push($notes, $noteGroup);
                }
            }

            if (Intervention::where('group_id', $groupId)->exists()) {
                $interventionsAux = Intervention::where('group_id', $groupId)->get();
                $interventions = $interventionsAux->merge($interventions);
            }
        }
        //sort active interventions by end date, and closed interventions by status (first unsolved, then solved)
        $interventionsActive = $interventions->where('status', '<', '3')->sortBy('end_day');
        $interventionsClosed = $interventions->where('status', '>', '2')->sortBy('status');
        $interventions = $interventionsActive->merge($interventionsClosed);



        return view('interventions', [
            "interventions" => $interventions,
            "edition_id" => $editionId,
            "notes" => $notes
        ]);
    }

    /**
     * Controller for editing interventions.
     * The reason, action, and starting and ending dates can be changed.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function editIntervention(Request $request, $interventionId)
    {
        $intervention          = Intervention::find($interventionId);

        //if the reason is of the format 'note\d', then the request will be empty,
        // so we should make sure the reason it is not updated with an empty string.
        if (preg_match("/^(note)\d+$/i", $intervention->reason) == false) {
            $intervention->reason = $request->get('editReason');
        }

        $intervention->action = $request->get('editAction');
        $intervention->start_day = $request->input('editStart'. $interventionId);
        $intervention->end_day = $request->input('editEnd' . $interventionId);

        $intervention->save();

        return back();
    }

    /**
     * Controller for creating interventions.
     * The request has the userId, reason, action, and starting and ending dates.
     *
     * @param Request $request
     * @param $editionId
     * @return RedirectResponse
     */
    public function createIntervention(Request $request, $editionId)
    {
        $intervention          = new Intervention();
        $userId = $request->get('createUser');
        $intervention->user_id = $userId;

        //find groupId based on user_id
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
        $intervention->status = 1; //active

        $intervention->save();

        return back();
    }

    /**
     * Create an intervention that is directly related to a note.
     *
     * @param Request $request
     * @param $noteId
     * @return RedirectResponse
     */
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

    /**
     * Controller for deleting interventions.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function deleteIntervention(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->delete();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to active.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function statusActive(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 1;
        $intervention->status_note = $request->get('active_note');
        $intervention->save();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to extended.
     * The status note and the new ending date are passed in the request.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function statusExtend(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 2;
        $intervention->end_day = $request->get('extend_end' . $interventionId);
        $intervention->status_note = $request->get('extend_note');
        $intervention->save();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to closed - unsolved.
     * The status note is passed in the request.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function statusUnsolved(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 3;
        $intervention->status_note = $request->get('unsolved_note');
        $intervention->save();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to closed - solved.
     * The status note is passed in the request.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function statusSolved(Request $request, $interventionId)
    {
        $intervention = Intervention::find($interventionId);
        $intervention->status = 4;
        $intervention->status_note = $request->get('solved_note');
        $intervention->save();
        return back();
    }
}
