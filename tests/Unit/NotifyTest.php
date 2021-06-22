<?php

namespace Tests\Unit;

use App\Models\CourseEditionUser;
use App\Models\Intervention;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotifyTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Test to see if the notify command sends a notification
     * for interventions with a passed deadline.
     */
    public function testDeadlinePassed()
    {
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
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
        $this->assertDatabaseCount('notifications', 0);
        Mail::fake();
        $this->artisan('notify');
        $this->assertDatabaseCount('notifications', 1);
    }

    /**
     * Test to see if the notify command sends a notification
     * for interventions with an approaching deadline.
     */
    public function testDeadlineApproaching()
    {
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
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
        Intervention::insert(
            [
                'user_id' => 1,
                'group_id' => 1,
                'reason' => '.',
                'action' => '.',
                'start_day' => '2021-05-31',
                'end_day' => Carbon::now()->addDays(2),
                'status' => 1,
                'visible_ta' => 1
            ]
        );
        $this->assertDatabaseCount('notifications', 0);
        Mail::fake();
        $this->artisan('notify');
        $this->assertDatabaseCount('notifications', 1);
    }
}
