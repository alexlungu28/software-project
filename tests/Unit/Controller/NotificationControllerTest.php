<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\User;
use App\Notifications\Deadline;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class NotificationControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Inserts the specified entries in the database,
     * mocks authentication and sends a notification.
     */
    public function before()
    {
        User::insert(
            [
                'org_defined_id' => '12345678',
                'net_id' => 'student1',
                'last_name' => 'Last',
                'first_name' => 'First',
                'email' => 'student1@student.tudelft.nl',
                'affiliation' => 'student'
            ]
        );
        User::insert(
            [
                'org_defined_id' => 'employee1',
                'net_id' => 'employee1',
                'last_name' => 'Last',
                'first_name' => 'First',
                'email' => 'employee1@tudelft.nl',
                'affiliation' => 'employee'
            ]
        );
        $user = User::find(2);
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2020
            ]
        );
        Auth::shouldReceive('user')->andReturn($user);
        Auth::shouldReceive('check')->andReturn(true);
        Group::insert(
            [
                'group_name' => 'Group 1',
                'content' => 'First group',
                'grade' => 10,
                'course_edition_id' => 1
            ]
        );
        Intervention::insert(
            [
                'user_id' => 1,
                'group_id' => 1,
                'reason' => '.',
                'action' => '.',
                'start_day' => '2021-05-31',
                'end_day' => '2021-06-04',
                'status' => 1,
                'visible_ta' => 1
            ]
        );
        Notification::send($user, new Deadline(Intervention::find(1), 'passed'));
    }

    /**
     * Test to verify that the notifications view is returned.
     */
    public function testView()
    {
        $this->before();
        $response = $this->get('/notifications/1');
        $response->assertViewIs('pages.notifications');
    }

    /**
     * Test to verify that a notification can be marked as read.
     */
    public function testMarkAsRead()
    {
        $this->before();
        $notification = DB::table('notifications')->where('notifiable_id', '=', 2)->get()->first();
        $this->assertNull($notification->read_at);
        $response = $this->put(
            '/notifications/markAsRead',
            [
                'id' => $notification->id,
                'edition_id' => 1
            ],
        );
        $notification = DB::table('notifications')->where('notifiable_id', '=', 2)->get()->first();
        $this->assertNotNull($notification->read_at);
        $response->assertStatus(302);
    }

    /**
     * Test to verify that all notifications are marked as read.
     */
    public function testMarkAllAsRead()
    {
        $this->before();
        $response = $this->put(
            '/notifications/markAllAsRead',
            [
                'edition_id' => 1
            ],
        );
        $response->assertStatus(302);
    }
}
