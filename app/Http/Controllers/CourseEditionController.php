<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseEdition;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseEditionController extends Controller
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
    public function create($course_id)
    {
        return view('courseEditions.courseEdition_create', [
            "course_id" => $course_id
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $course_id)
    {
        try {
            $course = Course::find($course_id);
            $year = $request->input('year');
            $data = ['course_id'=>$course_id, 'year'=>$year];
            DB::table('course_editions')->insert($data);
            return redirect('/');
        } catch (QueryException $e) {
            echo "Course edition already exists.<br/>";
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($course_id)
    {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $course_id)->get();
        return view('courseEditions.courseEdition_edit', ['course_id' => $course_id, 'courseEditions' => $courseEditions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $course_id)
    {
        try {
            $id = $request->input('id');
            $year = $request->input('year');
            $course = CourseEdition::find($id);
            $course->year = $year;
            $course->save();
        } catch (QueryException $e) {
            echo "Course edition already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
        return redirect('/');
    }

    public function delete($course_id) {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $course_id)->get();
        return view('courseEditions.courseEdition_delete', [
            "course_id" => $course_id,
            "courseEditions" => $courseEditions,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        CourseEdition::destroy($request->input('id'));
        return redirect('/');
    }

    public function view()
    {
        //
    }
}
