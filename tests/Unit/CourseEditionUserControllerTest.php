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
}

