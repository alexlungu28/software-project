<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\InterventionGroup;
use App\Models\Note;
use App\Models\NoteGroup;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;

class GroupInterventionsController extends Controller
{
    /**
     * Show all interventions of current course edition.
     *
     * @param $editionId
     * @return Application|Factory|View
     *
     * @codeCoverageIgnore
     */
    public function showAllGroupInterventions($editionId)
    {
        //fetching the notes and interventions of the current course edition.
        //first, the groupIds of the current course edition are collected,
        // then the notes and interventions are directly selected from the database and passed to the view.
//        $groupIds = DB::table('groups')
//            ->select('groups.id')
//            ->where('groups.course_edition_id', '=', $editionId)
//            ->pluck('groups.id');
//        $groupNotes = [];
//        $groupInterventions = [];
//
//
//        if (GroupIntervention::where('group_id', '=', $groupId)->exists()) {
//            $groupInterventionsAux = GroupIntervention::where('group_id', $groupId)->get();
//            $groupInterventions = $groupInterventionsAux->merge($groupInterventions);
//        }
//
//        if (NoteGroup::where('problem_signal', '>', 1)->where('group_id', $groupId)->exists()) {
//            $groupNotesAux = NoteGroup::where('problem_signal', '>', 1)->where('group_id', $groupId)->get();
//            foreach ($groupNotesAux as $groupNote) {
//                array_push($groupNotes, $groupNote);
//            }
//        }
//
//        $groupInterventions = $this->sortGroupInterventions($groupInterventions);
//
//        //$interventions = $interventionsActive->concat($interventionsClosed);
//
//        return view('/group_interventions/group_interventions_all_tab', [
//            "groupInterventions" => $groupInterventions,
//        ]);
    }

    /**
     * sort active interventions by end date,
     * and closed interventions by status (first unsolved, then solved)
     * @return $interventons
     *
     * @codeCoverageIgnore
     */
    public function sortGroupInterventions($groupInterventions)
    {
        $gInterventionsActive = [];
        $gInterventionsClosed = [];
        if ($groupInterventions != []) {
            if ($groupInterventions->where('status', '<', '3')->first() != null) {
                $gInterventionsActive = $groupInterventions->where('status', '<', '3')->sortBy('end_day');
            } else {
                $gInterventionsActive = [];
            }

            if ($groupInterventions->where('status', '>', '2')->first() != null) {
                $gInterventionsClosed = $groupInterventions->where('status', '>', '2')->sortBy('status');
            } else {
                $gInterventionsClosed = [];
            }
        }

        if ($gInterventionsActive == []) {
            $groupInterventions = $gInterventionsClosed;
        } elseif ($gInterventionsClosed == []) {
            $groupInterventions = $gInterventionsActive;
        } else {
            $groupInterventions = $gInterventionsActive->merge($gInterventionsClosed);
        }

        return $groupInterventions;
    }

    /**
     * Controller for editing interventions.
     * The reason, action, and starting and ending dates can be changed.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function editGroupIntervention(Request $request, $interventionId)
    {
        $groupIntervention          = InterventionGroup::find($interventionId);

        //if the reason is of the format 'note\d', then the request will be empty,
        // so we should make sure the reason it is not updated with an empty string.
        if (preg_match("/^(groupNote)\d+$/i", $groupIntervention->reason) == false) {
            $groupIntervention->reason = $request->get('editGroupReason');
        }

        $groupIntervention->action = $request->get('editGroupAction');
        $groupIntervention->start_day = $request->input('editGroupStart'. $interventionId);
        $groupIntervention->end_day = $request->input('editGroupEnd' . $interventionId);
        $groupIntervention->visible_ta = $request->get('editGroupVisibility' .$interventionId);

     //   return dd($request);

        $groupIntervention->save();

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
    public function createGroupIntervention(Request $request, $editionId)
    {
        $groupIntervention          = new InterventionGroup();


        $groupId = $request->get('createGroup');
        $groupIntervention->group_id = $groupId;

        $groupIntervention->reason = $request->get('createGroupReason');
        $groupIntervention->action = $request->get('createGroupAction');
        $groupIntervention->start_day = $request->input('createGroupStart' . $editionId);
        $groupIntervention->end_day = $request->input('createGroupEnd' . $editionId);
        $groupIntervention->status = 1; //active
        $groupIntervention->visible_ta = 1; //visible by default

        $groupIntervention->save();

        return back();
    }

    /**
     * Create an intervention that is directly related to a note.
     *
     * @param Request $request
     * @param $groupNoteId
     * @return RedirectResponse
     */
    public function createGroupInterventionNote(Request $request, $groupNoteId)
    {
        $groupIntervention          = new InterventionGroup();
        $groupNote = NoteGroup::find($groupNoteId);

        $groupIntervention->group_id = $groupNote->group_id;
        $groupIntervention->reason = "groupNote" . $groupNoteId;

        $groupIntervention->action = $request->get('createGroupAction');
        $groupIntervention->start_day = $request->input('createStartGroupNote' . $groupNoteId);
        $groupIntervention->end_day = $request->input('createEndGroupNote' . $groupNoteId);
        $groupIntervention->status = 1;
        $groupIntervention->visible_ta = 1; //visible by default

        $groupIntervention->save();

        return back();
    }

    /**
     * Controller for deleting interventions.
     *
     * @param Request $request
     * @param $interventionId
     * @return RedirectResponse
     */
    public function deleteGroupIntervention(Request $request, $interventionId)
    {
        $groupIntervention = InterventionGroup::find($interventionId);
        $groupIntervention->delete();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to active.
     *
     * @param Request $request
     * @param $groupInterventionId
     * @return RedirectResponse
     */
    public function statusGroupActive(Request $request, $groupInterventionId)
    {
        $groupIntervention = InterventionGroup::find($groupInterventionId);
        $groupIntervention->status = 1;
        $groupIntervention->status_note = $request->get('active_group_note');
        $groupIntervention->save();
        return back();
    }

    /**
     * Controller for changing the status of the intervention to extended.
     * The status note and the new ending date are passed in the request.
     *
     * @param Request $request
     * @param $groupInterventionId
     * @return RedirectResponse
     */
    public function statusGroupExtend(Request $request, $groupInterventionId)
    {
        $groupIntervention = InterventionGroup::find($groupInterventionId);
        $groupIntervention->status = 2;
        $groupIntervention->end_day = $request->get('extend_group_end' . $groupInterventionId);
        $groupIntervention->status_note = $request->get('extend_group_note');
        $groupIntervention->save();
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
    public function statusGroupUnsolved(Request $request, $groupInterventionId)
    {
        $groupIntervention = InterventionGroup::find($groupInterventionId);
        $groupIntervention->status = 3;
        $groupIntervention->status_note = $request->get('unsolved_group_note');
        $groupIntervention->save();
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
    public function statusGroupSolved(Request $request, $interventionId)
    {
        $groupIntervention = InterventionGroup::find($interventionId);
        $groupIntervention->status = 4;
        $groupIntervention->status_note = $request->get('solved_group_note');
        $groupIntervention->save();
        return back();
    }
}
