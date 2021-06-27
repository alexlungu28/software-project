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
     * Sets the group id based on the parameter present in the route.
     *
     * @param Request $request
     * @param $parameterNames
     * @param $groupId
     */
    private function setGroupId(Request $request, $parameterNames, &$groupId)
    {
        if (in_array('attendance_id', $parameterNames)) {
            $attendanceId = $request->route()->parameter('attendance_id');
            $groupId = Attendance::find($attendanceId)->group_id;
        } elseif (in_array('note_id', $parameterNames)) {
            $noteId = $request->route()->parameter('note_id');
            $groupId = Note::find($noteId)->group_id;
        } elseif (in_array('group_note_id', $parameterNames)) {
            $groupNoteId = $request->route()->parameter('group_note_id');
            $groupId = NoteGroup::find($groupNoteId)->group_id;
        } elseif (in_array('intervention_id', $parameterNames)) {
            $interventionId = $request->route()->parameter('intervention_id');
            $groupId = Intervention::find($interventionId)->group_id;
        } elseif (in_array('intervention_group_id', $parameterNames)) {
            $interventionGroupId = $request->route()->parameter('intervention_group_id');
            $groupId = InterventionGroup::find($interventionGroupId)->group_id;
        }
    }

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
        $parameterNames = $request->route()->parameterNames;
        if ($editionId == null) {
            if (in_array('course_edition_user_id', $parameterNames)) {
                $courseEditionUserId = $request->route()->parameter('course_edition_user_id');
                $editionId = CourseEditionUser::find($courseEditionUserId)->course_edition_id;
            } else {
                if ($groupId == null) {
                    $this->setGroupId($request, $parameterNames, $groupId);
                }
                $editionId = Group::find($groupId)->course_edition_id;
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
                if ($group == null && !(in_array($courseEditionUser->role, ['HeadTA', 'lecturer']))) {
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
