<?php

namespace Tests\Feature;

use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\Note;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Mockery;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;
}

    /**
     * Insert the specified entries inside the database tables.
     */
   /* public function before()
    {

        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(1);

        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer'
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
        Note::insert(
            [
                'content' => 'Note 1',
                'problem_signal' => 0,
                'noteable_id' => 1,
                'noteable_type' => 'App\Models\Group',
            ]
        );
        Note::insert(
            [
                'content' => 'Note 2',
                'problem_signal' => 1,
                'noteable_id' => 1,
                'noteable_type' => 'App\Models\Group',
            ]
        );
        Note::insert(
            [
                'content' => 'Note 3',
                'problem_signal' => 2,
                'noteable_id' => 1,
                'noteable_type' => 'App\Models\Group',
            ]
        );
        Note::insert(
            [
                'content' => 'Note 4',
                'problem_signal' => 1,
                'noteable_id' => 2,
                'noteable_type' => 'App\Models\Group',
            ]
        );
    }

    /**
     * Test to verify all groups show problems.
     *
     * @return void
     */
    /*
    public function testAllGroupsShowProblems()
    {
        $this->before();
        $this->get('/edition/1')
            ->assertSeeInOrder(
                array(
                    "Group 1",
                    "2 group problems",
                    "Group 2",
                    "1 group problem",
                    "Group 3"
                )
            )
            ->assertDontSee("0 group problems");
    }

    /**
     * Test to verify an individual group shows problems.
     *
     * @return void
     */
    /*
    public function testIndividualGroupShowProblemTable()
    {
        $this->before();
        $this->get('/group/1')->assertSeeInOrder(
            array(
                "Note 2",
                "1",
                "Note 3",
                "2",
            )
        )->assertDontSee("Note 1");
    }
}

