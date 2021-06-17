<?php

namespace Tests\Http\Controllers;

use App\Http\Controllers\AttendanceController;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Note;
use App\Models\NoteGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NotesControllerTest extends TestCase
{

    use WithoutMiddleware;
    use RefreshDatabase;

    //use DatabaseMigrations;

    private $controller;

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

        //adding another group to check if it is added to notes,
        //if the notes of the group was not accessed yet.
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
                'group_id' => 2,
            ]
        );

        Note::insert(
            [
                'user_id' => 2,
                'group_id' => 1,
                'week' => 1,
                'problem_signal' => 2,
                'note' => "Not that well!",
            ]
        );

        Note::insert(
            [
                'user_id' => 3,
                'group_id' => 1,
                'week' => 1,
                'problem_signal' => 1,
                'note' => "Good!",
            ]
        );

        NoteGroup::insert(
            [
                'group_id' => 1,
                'week' => 1,
                'problem_signal' => 2,
                'note' => "Not amazing!",
            ]
        );

    }

    /**
     * Check if the correct view is returned.
     *
     */
    public function testWeekGroup()
    {
        $this->before();
        $this->get('/note/1/1')->assertSeeText(
            array(
                "testfirstname1",
                "testlastname1",
                "Good!",
                "testfirstname2",
                "testlastname2",
                "Good!",
            )
        )->assertDontSee("testname3")->assertStatus(200);

        $this->assertDatabaseHas(
            'notes_individual',
            [
                'group_id' => 1,
                'week' => 1,
            ]
        );

        $this->assertDatabaseHas(
            'notes_group',
            [
                'group_id' => 1,
                'week' => 1,
            ]
        );
    }

    /**
     * Test to check if individual notes are correctly added
     * to the database when visiting '/attend/2/1'
     */
    public function testCreateIndividualNote()
    {
        $this->before();

        //check that the database does not have entries from group 2, week 1
        $this->assertDatabaseMissing(
            'notes_individual',
            [
                'group_id' => 2,
                'week' => 1
            ]
        );

        $this->get('/note/2/1')->
        assertSee(array(
            "testfirstname3",
            "testfirstname4",
            " "))->assertStatus(200);

        //check that the database now has entries from group 2, week 1
        $this->assertDatabaseHas(
            'notes_individual',
            [
                'group_id' => 2,
                'week' => 1,
            ]
        );
    }

    /**
     * Test to check if group notes are correctly added
     * to the database when visiting '/attend/2/1'
     */
    public function testCreateGroupNote()
    {
        $this->before();

        //check that the database does not have entries from group 2, week 1
        $this->assertDatabaseMissing(
            'notes_group',
            [
                'group_id' => 2,
                'week' => 1
            ]
        );

        $this->get('/note/2/1')->assertStatus(200);

        //check that the database now has entries from group 2, week 1
        $this->assertDatabaseHas(
            'notes_individual',
            [
                'group_id' => 2,
                'week' => 1,
            ]
        );
    }

    /**
     * Test to verify if individual note was successfully updated.
     */
    public function testUpdateIndividualNotes()
    {
        $this->before();
        $response = $this->post(
            '/noteUpdate/1',
            [
                'reason' => "Updated note!",
                'update' => 1,
            ]
        )->assertStatus(302);

        $this->assertDatabaseHas(
            'notes_individual',
            [
                'id' => 1,
                'problem_signal' => 1,
                'note' => "Updated note!",
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify if group note was successfully updated.
     */
    public function testUpdateGroupNotes()
    {
        $this->before();
        $response = $this->post(
            '/groupNoteUpdate/1',
            [
                'groupNoteUpdate' => 1,
                'groupNote' => "Updated note!",
            ]
        );

        $this->assertDatabaseHas(
            'notes_group',
            [
                'id' => 1,
                'problem_signal' => 1,
                'note' => "Updated note!",
            ]
        );
        $response->assertStatus(302);
    }
}
