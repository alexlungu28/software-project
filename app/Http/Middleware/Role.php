<?php

namespace App\Http\Middleware;

use App\Models\CourseEditionUser;
use Closure;
use Illuminate\Http\Request;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        //$request->attributes
        //$courseEditionId = CourseEditionUser::select('id')->where('course_edition_id', '=', $courseEdition)
        //    ->where('user_id', '=', $request->user()->id)->first->id;
        //$courseEditionUser = CourseEditionUser::find($courseEditionId);
        //if ($role != $courseEditionUser->role) {
        //  redirect('unauthorized');
        //}

        return $next($request);
    }
}
