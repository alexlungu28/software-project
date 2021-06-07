<?php

namespace Tests\Unit;

use App\Http\Controllers\CourseEditionUserController;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CourseEditionUserControllerTest extends TestCase
{

    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * @var CourseEditionUserController
     */
    private $controller;

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
        User::insert(
            [
                'id' => 1,
                'org_defined_id' => 5104444,
                'net_id' => 'testnetid',
                'last_name' => 'testlastname',
                'first_name'=> 'testfirstname',
                'email' => 'testnetid@test.test',
                'affiliation' => 'student',
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
        $this->controller = new CourseEditionUserController();
    }

    /**
     * Test to verify SetRoleTA create route.
     */
    public function testSetRoleTA()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
        $this->controller->setRoleTA(1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'TA',
            ]
        );
    }

    /**
     * Test to verify setRoleStudent create route.
     */
    public function testSetRoleStudent()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
        $this->controller->setRoleStudent(1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
    }

    /**
     * Test to verify SetRoleHeadTA create route.
     */
    public function testSetRoleHeadTA()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
        $this->controller->setRoleHeadTA(1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'HeadTA',
            ]
        );
    }
    /**
     * Test to modify the role of an already inserted courseEditionUser.
     */
    public function testInsertLecturerFromUsers()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'HeadTA',
            ]
        );
        $this->controller->insertLecturerFromUsers(1, 1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
    }
    /**
     * test to check if the entry is inserted if it was not in the db before.
     */
    public function testInsertLecturerFromUsersIf()
    {
        $this->before();
        $this->controller->insertLecturerFromUsers(1, 1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
    }

    /**
     * Test to modify the role of an already inserted courseEditionUser.
     */
    public function testInsertHeadTAFromUsers()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
        $this->controller->insertHeadTAFromUsers(1, 1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'HeadTA',
            ]
        );
    }

    /**
     * test to check if the entry is inserted if it was not in the db before.
     */
    public function testInsertHeadTAFromUsersIf()
    {
        $this->before();
        $this->controller->insertHeadTAFromUsers(1, 1);
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'HeadTA',
            ]
        );
    }

    /**
     * Test for the store method. it adds two group_users with their respective groups.
     */
    public function testAssignTaToGroupsStore()
    {
        $this->before();
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'HeadTA',
            ]
        );
        $response = $this->post(
            '/assignTaToGroups/{edition_id}/store',
            [
                'edition_id' => 1,
                'user_id' => 1,
                'groups' => array(1, 2),
            ],
        );
        $this->assertDatabaseHas(
            'group_user',
            [
                'user_id' => 1,
                'group_id' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'group_user',
            [
                'user_id' => 1,
                'group_id' => 2,
            ]
        );
    }
}

