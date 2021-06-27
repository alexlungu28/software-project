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
use Illuminate\Support\Facades\DB;

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
        return view('pages.notifications', [
            'edition_id' => $editionId,
            'notifications' => $unread
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
        $editionId = $request->input('edition_id');
        $individual = $request->input('individual');
        $unread = Auth::user()->unreadNotifications;
        if ($individual) {
            foreach ($unread as $notification) {
                if (isset($notification->data['Deadline passed'])) {
                    $notification->markAsRead();
                }
            }
        } else {
            foreach ($unread as $notification) {
                if (isset($notification->data['Deadline passed group'])) {
                    $notification->markAsRead();
                }
            }
        }
        return redirect('/notifications/' . $editionId);
    }

    /**
     * Returns the notification settings view.
     * @param $editionId
     * @return Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function viewSettings($editionId)
    {
        return view('pages.notificationSettings', ['edition_id' => $editionId]);
    }

    /**
     * Updates the notification settings of the logged in user,
     * or creates them if they do not exist in the database table.
     * @param Request $request
     * @return Application|RedirectResponse|Redirector
     */
    public function updateSettings(Request $request)
    {
        $individual = $request->input('individual');
        $group = $request->input('group');
        $editionId = $request->input('edition_id');
        DB::table('notification_settings')->updateOrInsert([
            'user_id' => Auth::user()->id
        ], [
            'user_deadlines' => $individual,
            'group_deadlines' => $group
        ]);
        return redirect('/notifications/' . $editionId . '/settings');
    }
}
