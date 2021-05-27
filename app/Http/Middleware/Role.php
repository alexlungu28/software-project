<?php

namespace App\Http\Middleware;

use App\Models\CourseEditionUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $editionId = $request->route()->parameter('edition_id');
        if ($editionId == null) {
            $groupId = $request->route()->parameter('group_id');
            if ($groupId != null) {
                $editionId = DB::table('groups')->select('course_edition_id')
                    ->where('id', '=', $groupId)
                    ->get()->first()->course_edition_id;
            } else {
                $courseEditionUserId = $request->route()->parameter('course_edition_user_id');
                if ($courseEditionUserId == null) {
                    return redirect('routeError');
                }
                $editionId = DB::table('course_edition_user')
                    ->where('id', '=', $courseEditionUserId)
                    ->get()->first()->course_edition_id;
            }
        }
        $courseEditionUser = DB::table('course_edition_user')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', $request->user()->id)
            ->get()->first();
        if ($courseEditionUser === null) {
            return redirect('unauthorized');
        } else {
            if (in_array($courseEditionUser->role, $roles)) {
                return $next($request);
            } else {
                return redirect('unauthorized');
            }
        }
    }
}
