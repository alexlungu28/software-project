<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\GroupInterventionsController;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\InterventionGroup;
use App\Models\Note;
use App\Models\NoteGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GroupInterventionsControllerTest extends TestCase
{
    use WithoutMiddleware;
    use RefreshDatabase;

    public function before()
    {
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(1);
        Auth::shouldReceive('user')->andReturn(null);

        User::insert(
            [
                'id' => 1,
                'org_defined_id' => 'testlecturer1',
                'net_id' => 'testlecturer1',
                'last_name' => 'testlecturer1',
                'first_name' => 'testlecturer2',
                'email' => 'testlecturer1@test.test',
                'affiliation' => 'lecturer',
            ]
        );

        Course::insert(
            [
                'id' => 1,
                'course_number' => 'CSE1234',
                'description' => 'Software Project',
            ]
        );
        CourseEdition::insert(
            [
                'id' => 1,
                'course_id' => 1,
                'year' => 2021,
            ]
        );

        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer'
            ]
        );

        CourseEditionUser::insert(
            [
                'user_id' => 2,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );

        CourseEditionUser::insert(
            [
                'user_id' => 3,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );

        CourseEditionUser::insert(
            [
                'user_id' => 4,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );

        CourseEditionUser::insert(
            [
                'user_id' => 5,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );

        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );

        Group::insert(
            [
                'group_name' => 'Group 2',
                'course_edition_id' => 1,
            ]
        );

        Group::insert(
            [
                'group_name' => 'Group 3',
                'course_edition_id' => 1,
            ]
        );

        User::insert(
            [
                'id' => 2,
                'org_defined_id' => 5104444,
                'net_id' => 'testnetid1',
                'last_name' => 'testlastname1',
                'first_name' => 'testfirstname1',
                'email' => 'testnetid1@test.test',
                'affiliation' => 'student',
            ]
        );

        User::insert(
            [
                'id' => 3,
                'org_defined_id' => 5104445,
                'net_id' => 'testnetid2',
                'last_name' => 'testlastname2',
                'first_name' => 'testfirstname2',
                'email' => 'testnetid2@test.test',
                'affiliation' => 'student',
            ]
        );

        GroupUser::insert(
            [
                'user_id' => 2,
                'group_id' => 1,
            ]
        );

        GroupUser::insert(
            [
                'user_id' => 3,
                'group_id' => 1,
            ]
        );

        User::insert(
            [
                'id' => 4,
                'org_defined_id' => 5104446,
                'net_id' => 'testnetid3',
                'last_name' => 'testlastname3',
                'first_name' => 'testfirstname3',
                'email' => 'testnetid3@test.test',
                'affiliation' => 'student',
            ]
        );

        User::insert(
            [
                'id' => 5,
                'org_defined_id' => 5104447,
                'net_id' => 'testnetid4',
                'last_name' => 'testlastname4',
                'first_name' => 'testfirstname4',
                'email' => 'testnetid4@test.test',
                'affiliation' => 'student',
            ]
        );

        GroupUser::insert(
            [
                'user_id' => 4,
                'group_id' => 2,
            ]
        );

        GroupUser::insert(
            [
                'user_id' => 5,
                'group_id' => 3,
            ]
        );


        NoteGroup::insert(
            [
                'group_id' => 3,
                'week' => 1,
                'problem_signal' => 3,
                'note' => "Not amazing!",
            ]
        );

        InterventionGroup::insert(
            [
                'id' => 1,
                'group_id' => 1,
                'reason' => 'attendance',
                'action' => 'Monitor commits',
                'start_day' => '2021-06-01',
                'end_day' => '2021-06-10',
                'status' => 1,
                'status_note' => '',
                'visible_ta' => 1
            ]
        );

        InterventionGroup::insert(
            [
                'id' => 2,
                'group_id' => 2,
                'reason' => 'note2',
                'action' => 'Monitor attendance',
                'start_day' => '2021-06-02',
                'end_day' => '2021-06-11',
                'status' => 4,
                'status_note' => '',
                'visible_ta' => 1
            ]
        );
    }


    /**
     * Test for controller that creates intervention.
     */
    public function testCreateGroupIntervention()
    {
        $this->before();

        $this->assertDatabaseMissing(
            'interventions_group',
            [
                'group_id' => 3,
            ]
        );

        $this->post(
            '/createGroupIntervention/1',
            [
                'createGroup' => 3,
                'createGroupReason' => "Did not work!",
                'createGroupAction' => 'Monitor contribution',
                'createGroupStart1' => '2021-03-03',
                'createGroupEnd1' => '2022-03-03',
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'group_id' => 3,
                'reason' => "Did not work!",
                'action' => "Monitor contribution",
                'status' => 1,
                'start_day' => '2021-03-03',
                'end_day' => '2022-03-03',
                'status' => 1,
            ]
        );
    }

    /**
     * Test for controller that creates interventions that is related to a note.
     */
    public function testCreateGroupInterventionNote()
    {
        $this->before();

        $this->assertDatabaseMissing(
            'interventions_group',
            [
                'group_id' => 3,
            ]
        );

        $this->post(
            '/createGroupInterventionNote/1',
            [
                'createGroupAction' => 'Monitor contribution',
                'createStartGroupNote1' => '2021-03-03',
                'createEndGroupNote1' => '2022-03-03',
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'reason' => "groupNote1",
                'action' => "Monitor contribution",
                'status' => 1,
                'start_day' => '2021-03-03',
                'end_day' => '2022-03-03',
                'status' => 1,
            ]
        );
    }


    /**
     * Test for controller that edits an intervention.
     */
    public function testEditIntervention()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'reason' => 'attendance',
                'action' => 'Monitor commits',
                'start_day' => '2021-06-01',
                'end_day' => '2021-06-10',
                'visible_ta' => 1
            ]
        );

        $this->post(
            '/editGroupIntervention/1',
            [
                'editGroupReason' => 'Edited reason!',
                'editGroupAction' => 'Edited action!',
                'editGroupStart1' => '2100-02-02',
                'editGroupEnd1' => '2101-03-03',
                'editGroupVisibility1' => '0',
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'reason' => 'Edited reason!',
                'action' => 'Edited action!',
                'start_day' => '2100-02-02',
                'end_day' => '2101-03-03',
                'visible_ta' => '0',
            ]
        );


    }


    /**
     * Test controller that changes the status of interventions to active.
     * We will change intervention 2 (status 4 closed - solved) to active.
     * The request contains the status note.
     *
     */
    public function testStatusActive()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 2,
                'status' => 4,
            ]
        );

        $this->post(
            '/statusGroupActive/2',
            [
                'active_group_note' => "Active again!",
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 2,
                'status' => 1,
                'status_note' => "Active again!",
            ]
        );
    }

    /**
     * Test controller that changes the status of intervention to extended.
     * Request contains the new extended date and the status note.
     */
    public function testStatusExtend()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 1,
                'end_day' => '2021-06-10',
                'status_note' => "",
            ]
        );

        $this->post(
            '/statusGroupExtend/1',
            [
                'extend_group_end1' => '2050-10-11',
                'extend_group_note' => "The deadline of this intervention was extended."
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 2,
                'end_day' => '2050-10-11',
                'status_note' => "The deadline of this intervention was extended.",
            ]
        );


    }

    /**
     * Test controller that changes the status of interventions to unsolved (3).
     * The request contains the status note.
     */
    public function testStatusUnsolved()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 1,
                'status_note' => "",
            ]
        );

        $this->post(
            '/statusGroupUnsolved/1',
            [
                'unsolved_group_note' => "Did not work enough!"
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 3,
                'status_note' => "Did not work enough!",
            ]
        );
    }

    /**
     * Test controller that changes the status of interventions to solved (4).
     * The request contains the status note.
     */
    public function testStatusSolved()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 1,
                'status_note' => "",
            ]
        );

        $this->post(
            '/statusGroupSolved/1',
            [
                'solved_group_note' => "Everything good!",
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 4,
                'status_note' => "Everything good!",
            ]
        );
    }

    /**
     * Test for controller that deletes interventions.
     */
    public function testDeleteIntervention()
    {
        $this->before();

        $this->assertDatabaseHas(
            'interventions_group',
            [
                'id' => 1,
                'status' => 1,
                'status_note' => "",
            ]
        );

        $this->post(
            '/deleteGroupIntervention/1')->assertStatus(302);

        $this->assertDatabaseMissing(
            'interventions_individual',
            [
                'id' => 1,
                'status' => 1,
                'status_note' => "",
            ]
        );
    }
}
