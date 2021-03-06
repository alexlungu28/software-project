<?php

namespace App\Http\Controllers;

use App\Models\CourseEditionUser;
use App\Models\GroupUser;
use App\Models\NoteGroup;
use DB;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Note;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotesController extends Controller
{

    /**
     * Display an overview of notes for all users of the course edition.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($editionId)
    {
        $users = CourseEditionUser::where('course_edition_id', $editionId)->where('role', 'student')->get(['user_id']);

        $notes = Note::all()->sortBy('week');

        // return Attendance::where('user_id', $id)->where('week', $week)->get();
        return view('notes')->with('notes', $notes)->with('edition_id', $editionId);

        // return $attendance;
    }

    /**
     * Update the the status of the notes.
     *
     * @param  $request
     * @param  $id - 1 green, 2 yellow, 3 red.
     * @return redirect
     */
    public function update(Request $request, $id)
    {
        $note          = Note::find($id);
        $note->problem_signal = $request->get('update');

        $note->note = $request->get('reason');

        $note->save();

        return redirect()->route('note', [$note->group_id, $note->week]);
    }

    /**
     * Update the the status of the group notes.
     *
     * @param  $request
     * @param  $id - 1 green, 2 yellow, 3 red.
     * @return redirect
     */
    public function groupNoteUpdate(Request $request, $id)
    {
        $groupNote          = NoteGroup::find($id);
        $groupNote->problem_signal = $request->get('groupNoteUpdate');
        $groupNote->note = $request->get('groupNote');

        $groupNote->save();

        return redirect()->route('note', [$groupNote->group_id, $groupNote->week]);
    }


    /**
     * Controller for route with weeks and groups. Creates attendance for entry
     * for all students based on group and week, only if they are not in the
     * database already. Otherwise returns view with the needed notes.
     *
     * @param $week
     * @param $group
     * @return Application|Factory|View
     */

    public function weekGroup($group, $week)
    {
        //fetching the editionId as it needs to be passed to the view.
        $editionId = DB::table('groups')->select('course_edition_id')
            ->where('id', '=', $group)->get()->first()->course_edition_id;

        //find group of students
        $usersGroup = GroupUser::all()->where('group_id', '=', $group);

        //list of students
        $users = [];

        foreach ($usersGroup as $item) {
            $user = User::find($item->user_id);
            array_push($users, $user);
        }

        //create and add attendance object to database
        //added only if it does not exist for the current group and week
        $notes = [];
        foreach ($users as $user) {
            if (CourseEditionUser::where('course_edition_id', '=', $editionId)
                                    ->where('user_id', '=', $user->id)
                                    ->where('role', '=', 'student')->exists()) {
                $id = $user->id;

                if (Note::where('user_id', '=', $id)
                        ->where('week', '=', $week)
                        ->where('group_id', '=', $group)
                        ->exists() === false) {
                    $this->createNote($user, $group, $week);
                }
            }
        }

        if (NoteGroup::where('group_id', '=', $group)
                ->where('week', '=', $week)
                ->exists() === false) {
            $this->createGroupNote($group, $week);
        }

        $groupNote = NoteGroup::where('group_id', '=', $group)
            ->where('week', '=', $week)->get();



        $notes = Note::select('*')
            ->where('group_id', '=', $group)
            ->where('week', '=', $week)->get();


        return view('notes')
                ->with('notes', $notes)->with('groupNotes', $groupNote)
                ->with('edition_id', $editionId)
                ->with('week', $week)->with('group_id', $group);
    }


    /**
     * Function that creates a new individual note object and adds it to the database.
     * This function is only called when no entry for a student in a specific week exists.
     * @param $user - user_id
     * @param $group - group_id
     * @param $week - week number
     */
    public function createNote($user, $group, $week)
    {
                $id = $user->id;

                $note          = new Note();
                $note->user_id = $id;
                $note->group_id = $group;
                $note->week    = $week;
                $note->problem_signal = null;
                $note->note  = null;
                $note->save();
    }

    /**
     * Function that creates a new group note object and adds it to the database.
     * This function is only called when no entry for a group in a specific week exists.
     * @param $user - user_id
     * @param $group - group_id
     * @param $week - week number
     */
    public function createGroupNote($group, $week)
    {

        $note          = new NoteGroup();
        $note->group_id = $group;
        $note->week    = $week;
        $note->problem_signal = null;
        $note->note  = null;
        $note->save();
    }
}
