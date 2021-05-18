<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
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
        $course_number = $request->input('course_number');
        $description = $request->input('description');
        $data=array('course_number'=>$course_number, 'description'=>$description, 'created_at' =>now(), 'updated_at' => now());
        DB::table('courses')->insert($data);
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
        $course_number = $request->input('course_number');
        $description = $request->input('description');
        $course = Course::find($id);
        $course->course_number = $course_number;
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

    public function view()
    {
        $courses = Course::all();
        return view('allcourses', [
            "courses" => $courses,
        ]);
    }

    public function viewCourseById()
    {
    }
}
