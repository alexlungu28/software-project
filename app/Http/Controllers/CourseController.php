<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEdition;
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
        return view('course_create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //adding the course inside the courses table
        $courseNumber = $request->input('course_number');
        $description = $request->input('description');
        $data=array('course_number'=>$courseNumber, 'description'=>$description, 'created_at' =>now(),
            'updated_at' => now());
        DB::table('courses')->insert($data);

        //adding the course edition inside the course editions table
        $year = $request->input('year');
        $courseId = DB::table('courses')->select('id')
            ->where('course_number', '=', $courseNumber)->get()->first()->id;
        DB::table('course_editions')->insert(array('course_id'=>$courseId,
            'year'=>$year, 'created_at'=>now(),'updated_at'=>now()));

        //adding the user role inside the pivot table
        $courseEditionId = DB::table('course_editions')->select('id')
            ->where('course_id', '=', $courseId)->get()->first()->id;
        DB::table('course_edition_user')->insert(array('user_id'=>$request->user()->id,
            'course_edition_id'=>$courseEditionId, 'role'=>'lecturer' ,'created_at'=>now(),'updated_at'=>now()));

        return redirect('/');
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
        return view('course_edit', ['courses' => $this::getAllCourses()]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    public function update(Request $request)
    {
        $id = $request->input('id');
        $courseNumber = $request->input('course_number');
        $description = $request->input('description');
        $course = Course::find($id);
        $course->course_number = $courseNumber;
        $course->description = $description;
        $course->save();
        return redirect('/');
    }

    public function delete()
    {
        $courses = $this::getAllCourses();
        return view('course_delete', [
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

    public function viewEmployee()
    {
        $courses = Course::all();
        return view('mainEmployee', [
            "courses" => $courses,
        ]);
    }

    public function viewStudent()
    {
        $courses = Course::all();
        return view('mainStudent', [
            "courses" => $courses,
        ]);
    }

    public function view()
    {
        $user = Auth::user();
        if ($user->affiliation === 'employee') {
            return $this->viewEmployee();
        } else {
            return $this->viewStudent();
        }
    }

    public function viewCourseById($id)
    {
        $courseEditions = CourseEdition::all();
        return view('allCourseEditions', [
            "course_id" => $id,
            "courseEditions" => $courseEditions,
        ]);
    }
}
