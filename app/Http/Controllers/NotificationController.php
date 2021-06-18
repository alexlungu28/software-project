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
     * Returns the notifications view based on the course edition id.
     *
     * @param $editionId
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
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

    /**
     * Marks all notifications of the logged in user as read.
     *
     * @param Request $request
     * @return Application|Redirector|RedirectResponse
     */
    public function markAllAsRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();
        $editionId = $request->input('edition_id');
        return redirect('/notifications/' . $editionId);
    }
}
