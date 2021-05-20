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
    public function create($courseId)
    {
        return view('courseEditions.courseEdition_create', [
            "course_id" => $courseId
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $courseId)
    {
        try {
            $course = Course::find($courseId);
            $year = $request->input('year');
            $data = ['course_id'=>$courseId, 'year'=>$year];
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
    public function edit($courseId)
    {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $courseId)->get();
        return view('courseEditions.courseEdition_edit',
            ['course_id' => $courseId, 'courseEditions' => $courseEditions]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $id = $request->input('id');
            $year = $request->input('year');
            $courseEdition = CourseEdition::find($id);
            $courseEdition->year = $year;
            $courseEdition->save();
        } catch (QueryException $e) {
            echo "Course edition already exists.<br/>";
            echo "Redirecting you back to main page...";
            header("refresh:3;url=/");
        }
        return redirect('/');
    }

    /**
     * Return the view for deleting course editions.
     *
     * @param $courseId
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function delete($courseId)
    {
        $courseEditions = DB::table('course_editions')->where('course_id', '=', $courseId)->get();
        return view('courseEditions.courseEdition_delete', [
            "course_id" => $courseId,
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
