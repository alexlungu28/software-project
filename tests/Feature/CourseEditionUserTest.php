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

class CourseEditionUserTest extends TestCase
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
                'first_name' => 'testfirstname',
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
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
        $this->controller = new CourseEditionUserController();
    }
    public function testCourseEditionUserView()
    {
        $this->before();
        $response = $this->get('/studentList/1')
            ->assertSee('student')
            ->assertDontSee('lecturer');
        $response->assertStatus(200);
    }
}
