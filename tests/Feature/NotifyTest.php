<?php

namespace Tests\Feature;

use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\InterventionGroup;
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
     * for individual interventions with a passed deadline.
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
        Group::insert(
            [
                'group_name' => 'Group 1',
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
        $this->assertDatabaseCount('notifications', 0);
        Mail::fake();
        $this->artisan('notify');
        $this->assertDatabaseCount('notifications', 1);
    }

    /**
     * Test to see if the notify command sends a notification
     * for individual interventions with an approaching deadline.
     */
    public function testDeadlineApproaching()
    {
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
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
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1
            ]
        );
        GroupUser::insert(
            [
                'user_id' => 1,
                'group_id' => 1
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

    /**
     * Test to see if the notify command sends a notification
     * for group interventions with a passed deadline.
     */
    public function testDeadlinePassedGroup()
    {
        CourseEditionUser::insert(
            [
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
        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1
            ]
        );
        InterventionGroup::insert(
            [
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
     * for group interventions with an approaching deadline.
     */
    public function testDeadlineApproachingGroup()
    {
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
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
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1
            ]
        );
        GroupUser::insert(
            [
                'user_id' => 1,
                'group_id' => 1
            ]
        );
        InterventionGroup::insert(
            [
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

    /**
     * Test to verify that TAs receive notifications for passed deadlines
     * within the groups they monitor.
     */
    public function testNotifyTAs()
    {
        User::insert(
            [
                'org_defined_id' => '12345678',
                'net_id' => 'student1',
                'last_name' => 'Last',
                'first_name' => 'First',
                'email' => 'student2@student.tudelft.nl',
                'affiliation' => 'student'
            ]
        );
        GroupUser::insert(
            [
                'user_id' => 1,
                'group_id' => 1
            ]
        );
        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'TA'
            ]
        );
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        InterventionGroup::insert(
            [
                'group_id' => 1,
                'reason' => '.',
                'action' => '.',
                'start_day' => '2021-05-31',
                'end_day' => '2021-06-04',
                'status' => 1,
                'visible_ta' => 1
            ]
        );
        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1
            ]
        );
        $this->assertDatabaseCount('notifications', 0);
        Mail::fake();
        $this->artisan('notify');
        $this->assertDatabaseCount('notifications', 1);
    }
}
