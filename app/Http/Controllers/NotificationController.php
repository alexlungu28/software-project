<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller as BaseController;

class NotificationController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

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

    public function view($editionId)
    {
        $unread = Auth::user()->unreadNotifications;
        $users = $unread->map(function ($notification) {
            return User::where('id', '=', $notification->data['Deadline passed']['user_id'])->get()->first();
        });
        return view('pages.notifications', [
            'edition_id' => $editionId,
            'notifications' => $unread,
            'users' => $users
        ]);
    }

    /**
     * Marks a notification as read.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function markAsRead(Request $request)
    {
        $id = $request->input('id');
        $editionId = $request->input('edition_id');
        Auth::user()->unreadNotifications->map(function ($notification) use ($id) {
            if ($notification->id === $id) {
                $notification->markAsRead();
            }
        });
        return redirect('/notifications/' . $editionId);
    }
}
