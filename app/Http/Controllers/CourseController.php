<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEdition;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('courses.course_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            //adding the course inside the courses table
            $courseNumber = $request->input('course_number');
            $description = $request->input('description');
            $data = array('course_number' => $courseNumber, 'description' => $description, 'created_at' => now(),
                'updated_at' => now());
            DB::table('courses')->insert($data);
            return redirect('/');
        } catch (QueryException $e) {
            echo "Course number already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Retrieve all courses.
     *
     * @return Course[]|\Illuminate\Database\Eloquent\Collection
     */
    public static function getAllCourses()
    {
        return Course::all();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        return view('courses.course_edit', ['courses' => $this::getAllCourses()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
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
        } catch (QueryException $e) {
            echo "Course number already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
        return redirect('/');
    }

    /**
     * Return the view for deleting courses.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function delete()
    {
        $courses = $this::getAllCourses();
        return view('courses.course_delete', [
            "courses" => $courses,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Course::destroy($request->input('id'));
        return redirect('/');
    }

    /**
     * Return the employee view of courses.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewStudent()
    {
        //TODO: query the database to show only the courses where the student is registered to
        $courses = Course::all();
        return view('courses.mainStudent', [
            "courses" => $courses,
        ]);
    }

    /**
     * Return the courses view based on user affiliation.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewEmployeeCE($id)
    {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $id)->get();
        return view('courseEditions.courseEditionEmployee', [
            "course_id" => $id,
            "courseEditions" => $courseEditions,
        ]);
    }

    /**
     * Return the student view of course editions.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewStudentCE($id)
    {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $id)->get();
        return view('courseEditions.courseEditionStudent', [
            "course_id" => $id,
            "courseEditions" => $courseEditions,
        ]);
    }

    /**
     * Return the course edition view based on user affiliation.
     *
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
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
