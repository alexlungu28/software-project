<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\Intervention;
use App\Models\InterventionGroup;
use App\Models\Note;
use App\Models\NoteGroup;
use App\Models\Rubric;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/** *  @SuppressWarnings(PHPMD) */
class GroupController extends Controller
{
    /**
     * Return the group view based on the group id.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function view($id)
    {
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $id)->get()->first()->course_edition_id;
        $role = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', Auth::id())->get()->first()->role;

        $usersFromGroup = DB::table('group_user')->select('user_id')->where('group_id', '=', $id)->get();
        $gitanalyses = DB::table('gitanalyses')->where('group_id', '=', $id)->get();


        $notesNoInterventions = $this->getNotesNoInterventions($id);
        $gNotesNoInterv = $this->getGroupNotesNoInterventions($id);

        $gInterventions = Group::find($id)->groupGroupInterventions->sortBy('end_day')->sortBy('status');
        $interventions = Group::find($id)->groupIndividualInterventions->sortBy('end_day')->sortBy('status');


            return view('weeks', ['edition_id' => $editionId, 'group_id' => $id,
                'group' => Group::find($id), 'users' => $usersFromGroup,
                'gitanalyses' => $gitanalyses,
                'groupNotesNoInterventions' => $gNotesNoInterv,
                'notesNoInterventions' => $notesNoInterventions,
                'interventions' => $interventions,
                'groupInterventions' => $gInterventions]);
    }

    public function userSummary($courseUserId)
    {
        $courseUser =CourseEditionUser::find($courseUserId);
        $editionId = $courseUser->course_edition_id;
        $userId = $courseUser->user_id;

        $userLoggedIn =auth()->user();
        $userLoggedInId = $userLoggedIn->id;
        $role = CourseEditionUser::where('user_id', '=', $userLoggedInId)
                                    ->where('course_edition_id', '=', $editionId)
                                    ->first()->role;


        $user = User::find($userId);

        $groupId = DB::table('group_user')
            ->join('course_edition_user', 'group_user.user_id', '=', 'course_edition_user.user_id')
            ->select('group_user.group_id')
            ->where('group_user.user_id', '=', $userId)
            ->where('course_edition_user.course_edition_id', '=', $editionId)
            ->pluck('group_user.group_id')->first();


        $attendances = Attendance::where('user_id', '=', $userId)
                                    ->where('group_id', '=', $groupId)
                                    ->orderBy('week')->get();

        $notes = Note::where('user_id', '=', $userId)
                        ->where('group_id', '=', $groupId)
                        ->orderBy('week')->get();

        $groupNotes = NoteGroup::where('group_id', '=', $groupId)
                                    ->orderBy('week')->get();

        $interventions = Intervention::where('user_id', '=', $userId)
                                        ->where('group_id', '=', $groupId)
                                        ->orderBy('end_day')->get();

        $groupInterventions = InterventionGroup::where('group_id', '=', $groupId)
                                                    ->orderBy('end_day')->get();


        return view('user_summary', [
                'attendances' => $attendances, 'user'=> $user, 'edition_id' => $editionId,
                'notes' => $notes, 'groupNotes'=>$groupNotes,
                'interventions' => $interventions, 'groupInterventions' => $groupInterventions,
                'role' => $role
            ]);
    }

    /**
     * Function that returns the list of group notes that
     * do not have interventions related to them.
     * This is needed in the 'problem cases' subview.
     * @param $id group_id
     * @return array
     *
     */
    public function getGroupNotesNoInterventions($id)
    {
        $gNotesGood = [];
        $gNotes = NoteGroup::where('group_id', $id)->orderByDesc('week')->get();
        foreach ($gNotes as $groupNote) {
            array_push($gNotesGood, $groupNote);
        }
        $gInterventions = InterventionGroup::where('group_id', $id)->get();
        $gInterventionNotes = [];
        foreach ($gInterventions as $intervention) {
            if (preg_match("/^(groupNote)\d+$/i", $intervention->reason)) {
                $gNote = NoteGroup::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                array_push($gInterventionNotes, $groupNote);
            }
        }
        $gNotesNoInterv = array_diff($gNotesGood, $gInterventionNotes);

        return $gNotesNoInterv;
    }


    /**
     * Function that returns the list of individual notes that
     * do not have interventions related to them.
     * This is needed in the 'problem cases' subview.
     * @param $id group_id
     * @return list of notes that do not have related interventions yet.
     *
     */
    public function getNotesNoInterventions($id)
    {
        $notesGood = [];
        $notes = Note::where('group_id', $id)->orderByDesc('week')->get();
        foreach ($notes as $note) {
            array_push($notesGood, $note);
        }
        $interventions = Intervention::where('group_id', $id)->get();
        $interventionNotes = [];
        foreach ($interventions as $intervention) {
            if (preg_match("/^(note)\d+$/i", $intervention->reason)) {
                $note = Note::find(preg_replace('/[^0-9]/', '', $intervention->reason));
                array_push($interventionNotes, $note);
            }
        }
        $notesNoInterventions = array_diff($notesGood, $interventionNotes);

        return $notesNoInterventions;
    }

    public function viewWeek($id, $week)
    {
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $id)->get()->first()->course_edition_id;

        $courseRubrics = Rubric::all()->where('course_edition_id', '=', $editionId);

        $rubrics = $courseRubrics->where('week', '=', $week)->merge($courseRubrics->where('week', '=', null));

        $role = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', Auth::id())->get()->first()->role;

        $gitanalyses = DB::table('gitanalyses')->where('group_id', '=', $id)->where('week_number', '=', $week)->get();

        if ($role === 'lecturer') {
            return view('week', ['edition_id' => $editionId, 'group_id' => $id,
                'week' => $week, 'rubrics' => $rubrics, 'gitanalyses' => $gitanalyses]);
        } else {
            return view('weekTA', ['edition_id' => $editionId, 'group_id' => $id,
                'week' => $week, 'rubrics' => $rubrics, 'gitanalyses' => $gitanalyses]);
        }
    }
}
