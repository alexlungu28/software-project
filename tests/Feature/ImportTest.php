<?php

namespace Tests\Feature;

use App\Imports\CourseEditionStudentImport;
use App\Imports\CourseEditionTAImport;
use App\Imports\GroupsImport;
use App\Imports\GroupsTAImport;
use App\Imports\GroupUserImport;
use App\Imports\GroupUserTAImport;
use App\Imports\UsersImport;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * @var $row - a row in the csv file imported
     */
    private $row;

    /**
     * Test to verify UsersImport model function.
     */
    public function testUsersModel()
    {
        $this->row = ([
            'orgdefinedid' => 5104444,
            'username' => 'student1',
            'last_name' => 'testlastname',
            'first_name' => 'testfirstname',
            'email' => "f.last@tudelft.nl",
            'groups' => 'Group 1',
            'group_text_grade_text' => '',
        ]);
        $usersImport = new UsersImport();

        $usersImport->model($this->row)->save();
        $this->assertDatabaseHas(
            'users',
            [
                'id' => 1,
                'org_defined_id' => 5104444,
                'net_id' => 'student1',
                'last_name' => 'testlastname',
                'first_name' => 'testfirstname',
                'email' => 'f.last@tudelft.nl',
                'affiliation' => 'student',
            ]
        );

        $this->assertNull($usersImport->model($this->row));
    }

    /**
     * Test to verify CourseEditionTAImport model function.
     */
    public function testTaCourseEditionModel()
    {
        $this->testUsersModel();
        $cEditionTaImport = new CourseEditionTAImport(1);

        $cEditionTaImport->model($this->row)->save();
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'TA',
            ]
       );

        $this->assertNull($cEditionTaImport->model($this->row));
    }

    /**
     * Test to verify CourseEditionStudentImport model function.
     */
    public function testStudentCourseEditionModel()
    {
        $this->testUsersModel();

        $cEStudentImport = new CourseEditionStudentImport(1);


        $cEStudentImport->model($this->row)->save();
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );

        $this->assertNull($cEStudentImport->model($this->row));
    }

    /**
     * Test to verify GroupsImport model function.
     */
    public function testGroupsModel()
    {
        $this->testUsersModel();

        $groupsImport = new GroupsImport(1);


        $groupsImport->model($this->row)->save();
        $this->assertDatabaseHas(
            'groups',
            [
                'id' => 1,
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );

        $this->assertNull($groupsImport->model($this->row));
    }

    /**
     * Test to verify GroupsTAImport model function.
     */
    public function testGroupsTAModel()
    {
        $this->testUsersModel();

        $groupsTAImport = new GroupsTAImport(1);

        $this->row = ([
            'orgdefinedid' => 5104444,
            'username' => 'student1',
            'last_name' => 'last',
            'first_name' => 'first',
            'email' => 'student1@tudelft.nl',
            'groups' => 'Group 1; Group 2',
        ]);

        $groupsTAImport->model($this->row);
        $this->assertDatabaseHas(
            'groups',
            [
                'id' => 1,
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );

        $this->assertDatabaseHas(
            'groups',
            [
                'id' => 2,
                'group_name' => 'Group 2',
                'course_edition_id' => 1,
            ]
        );

        $this->assertNull($groupsTAImport->model($this->row));
    }

    /**
     * Test to verify GroupsImport model function.
     */
    public function testGroupUserModel()
    {
        $this->testUsersModel();

        $groupUserImport = new GroupUserImport(1);

        Group::insert(
            [
                'id' => 1,
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );


        $groupUserImport->model($this->row)->save();
        $this->assertDatabaseHas(
            'group_user',
            [
                'id' => 1,
                'user_id' => 1,
                'group_id' => 1,
            ]
        );

        $this->assertNull($groupUserImport->model($this->row));
    }

    /**
     * Test to verify GroupsTAImport model function.
     */
    public function testGroupUserTAModel()
    {
        $this->testUsersModel();

        $groupUserTAImport = new GroupUserTAImport(1);

        Group::insert(
            [
                'id' => 1,
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );

        Group::insert(
            [
                'id' => 2,
                'group_name' => 'Group 2',
                'course_edition_id' => 1,
            ]
        );

        $this->row = ([
            'orgdefinedid' => 5104444,
            'username' => 'student1',
            'last_name' => 'last',
            'first_name' => 'first',
            'email' => 'student1@tudelft.nl',
            'groups' => 'Group 1; Group 2',
        ]);

        $groupUserTAImport->model($this->row);
        $this->assertDatabaseHas(
            'groups',
            [
                'id' => 1,
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
            ]
        );

        $this->assertDatabaseHas(
            'groups',
            [
                'id' => 2,
                'group_name' => 'Group 2',
                'course_edition_id' => 1,
            ]
        );

        $this->assertNull($groupUserTAImport->model($this->row));
    }

}
