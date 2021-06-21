<?php

namespace App\Http\Middleware;

use App\Models\Attendance;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\Intervention;
use App\Models\InterventionGroup;
use App\Models\Note;
use App\Models\NoteGroup;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @param mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $editionId = $request->route()->parameter('edition_id');
        $groupId = $request->route()->parameter('group_id');
        if ($editionId == null) {
            if ($groupId != null) {
                $editionId = Group::find($groupId)->course_edition_id;
            } else {
                $courseEditionUserId = $request->route()->parameter('course_edition_user_id');
                if ($courseEditionUserId != null) {
                    $editionId = CourseEditionUser::find($courseEditionUserId)->course_edition_id;
                } else {
                    $attendanceId = $request->route()->parameter('attendance_id');
                    if ($attendanceId != null) {
                        $groupId = Attendance::find($attendanceId)->group_id;
                        $editionId = Group::find($groupId)->course_edition_id;
                    } else {
                        $noteId = $request->route()->parameter('note_id');
                        if ($noteId != null) {
                            $groupId = Note::find($noteId)->group_id;
                            $editionId = Group::find($groupId)->course_edition_id;
                        } else {
                            $groupNoteId = $request->route()->parameter('group_note_id');
                            if ($groupNoteId != null) {
                                $groupId = NoteGroup::find($groupNoteId)->group_id;
                                $editionId = Group::find($groupId)->course_edition_id;
                            } else {
                                $interventionId = $request->route()->parameter('intervention_id');
                                if ($interventionId != null) {
                                    $groupId = Intervention::find($interventionId)->group_id;
                                    $editionId = Group::find($groupId)->course_edition_id;
                                } else {
                                    $interventionGroupId = $request->route()->parameter('intervention_group_id');
                                    if ($interventionGroupId != null) {
                                        $groupId = InterventionGroup::find($interventionId)->group_id;
                                        $editionId = Group::find($groupId)->course_edition_id;
                                    } else {
                                        abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', $request->user()->id)
            ->get()->first();
        if ($courseEditionUser === null) {
            abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
        } else {
            if ($groupId != null) {
                $group = DB::table('group_user')
                    ->where('user_id', '=', $request->user()->id)
                    ->where('group_id', '=', $groupId)->get()->first();
                if ($group == null) {
                    abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
                }
            }
            if (in_array($courseEditionUser->role, $roles)) {
                return $next($request);
            } else {
                abort(Response::HTTP_FORBIDDEN, 'Unauthorized');
            }
        }
    }
}
