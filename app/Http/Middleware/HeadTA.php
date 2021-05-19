<?php

namespace App\Http\Middleware;

use App\Models\CourseEditionUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HeadTA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $courseEdition)
    {

        $courseEditionId = CourseEditionUser::select('id')->where('course_edition_id', '=', $courseEdition)
            ->where('user_id', '=', $request->user()->id)->first->id;
        $courseEditionUser = CourseEditionUser::find($courseEditionId);
        if (! 'headTA' == $courseEditionUser->role) {
            redirect('unauthorized');
        }

        return $next($request);
    }
}
