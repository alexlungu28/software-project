<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function view($editionId) {
        $interventions = DB::table('course_edition_user')->where('course_edition_id', '=', $editionId)->get()
            ->flatMap(function ($editionUser) {
               $userInterventions = DB::table('interventions_individual')
                   ->where('user_id', '=', $editionUser->user_id)->get();
               $currentDate = Carbon::now();
               $passed = $userInterventions->map(function ($intervention) use ($currentDate) {
                   //ddd($intervention->end_day);
                   if ($currentDate->gt($intervention->end_day)) {
                       return $intervention;
                   }
                   return null;
               })->filter(function ($intervention) {
                   return $intervention != null;
               })->unique();
               return $passed;
            })->filter(function ($intervention) {
                return $intervention != null;
            })->unique();
        //ddd($interventions);
        $users = $interventions->map(function ($intervention) {
            return DB::table('users')->where('id', '=', $intervention->user_id)->get()->first();
        });
        //ddd($users[0]);
        //ddd($interventions);
        return view('pages.notifications', [
            'edition_id' => $editionId,
            'interventions' => $interventions,
            'notifications' => [],
            'users' => $users
        ]);
    }
}
