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
    public function handle(Request $request, Closure $next)
    {
        //TODO: Add views for TA and Head TA and redirect them to those
        $editionId = $request->route()->parameter('edition_id');
        $courseEditionId = DB::table('course_edition_user')->select('id')
            ->where('course_edition_id', '=', $editionId)
            ->where('user_id', '=', $request->user()->id)
            ->get()->first()->id;
        $courseEditionUser = CourseEditionUser::find($courseEditionId);
        if ($courseEditionUser->role == 'lecturer') {
            return $next($request);
        } else {
            redirect('unauthorized');
        }
    }
}
