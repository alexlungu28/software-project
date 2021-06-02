<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEdition;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(Request $request)
    {
        try {
            //adding the course inside the courses table
            $courseNumber = $request->input('course_number');
            $description = $request->input('description');
            $data = array('course_number' => $courseNumber, 'description' => $description, 'created_at' => now(),
                'updated_at' => now());
            // TODO: make it possible to add a course if the course number matches with a soft deleted one
            DB::table('courses')->insert($data);
            return redirect('/');
        } catch (QueryException $e) {
            echo "Course number already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
    }


    /**
     * Retrieve all courses.
     *
     * @return Course[]|Collection
     */
    public static function getAllCourses()
    {
        return Course::all();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request)
    {
        try {
            $id = $request->input('id');
            $courseNumber = $request->input('course_number');
            $description = $request->input('description');
            $course = Course::find($id);
            $course->course_number = $courseNumber;
            $course->description = $description;
            $course->save();
            return redirect('/');
        } catch (QueryException $e) {
            echo "Course number already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(Request $request)
    {
        Course::destroy($request->input('id'));
        return redirect('/');
    }

    /**
     * Return the employee view of courses.
     *
     * @return Application|Factory|View
     */
    public function viewEmployee()
    {
        $courses = Course::all();
        return view('courses.mainEmployee', [
            "courses" => $courses,
        ]);
    }

    /**
     * Return the student view of courses.
     *
     * @return Application|Factory|View
     */
    public function viewStudent()
    {
        $courses = DB::table('group_user')->where('user_id', '=', Auth::id())->get()->map(function ($groupUser) {
            $editionId = DB::table('groups')->where('id', '=', $groupUser->group_id)
                ->get()->first()->course_edition_id;
            $courseId = DB::table('course_editions')->where('id', '=', $editionId)
                ->get()->first()->course_id;
            $course = DB::table('courses')->where('id', '=', $courseId)
                ->get()->first();
            $role = DB::table('course_edition_user')->where('user_id', '=', Auth::id())
                ->where('course_edition_id', '=', $editionId)->get()->first()->role;
            if ($role === 'TA' || $role === 'HeadTA') {
                return $course;
            }
            return null;
        })->filter(function ($course) {
            return $course != null;
        });
        return view('courses.mainStudent', [
            "courses" => $courses,
        ]);
    }

    /**
     * Return the courses view based on user affiliation.
     *
     * @return Application|Factory|View
     */
    public function view()
    {
        $user = Auth::user();
        if ($user->affiliation === 'employee') {
            return $this->viewEmployee();
        } else {
            return $this->viewStudent();
        }
    }

    /**
     * Return the employee view of course editions.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function viewEmployeeCE($id)
    {
        $courseEditions = CourseEdition::where('course_id', '=', $id)->get();
        return view('courseEditions.courseEditionEmployee', [
            "course_id" => $id,
            "courseEditions" => $courseEditions,
        ]);
    }

    /**
     * Return the student view of course editions.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function viewStudentCE($id)
    {
        $courseEditions = DB::table('course_editions')
            ->where('course_id', '=', $id)->get()->map(function ($courseEdition) {
                $courseEditionUser = DB::table('course_edition_user')
                    ->where('course_edition_id', '=', $courseEdition->id)
                    ->where('user_id', '=', Auth::id())->get()->first();
                if ($courseEditionUser !== null
                    && ($courseEditionUser->role === 'TA' || $courseEditionUser->role === 'HeadTA')) {
                    return $courseEdition;
                }
                return null;
            })->filter(function ($courseEdition) {
                return $courseEdition != null;
            });
        return view('courseEditions.courseEditionStudent', [
            "course_id" => $id,
            "courseEditions" => $courseEditions,
        ]);
    }

    /**
     * Return the course edition view based on user affiliation.
     *
     * @param $id
     * @return Application|Factory|View
     */
    public function viewCourseById($id)
    {
        $user = Auth::user();
        if ($user->affiliation === 'employee') {
            return $this->viewEmployeeCE($id);
        } else {
            return $this->viewStudentCE($id);
        }
    }
}
