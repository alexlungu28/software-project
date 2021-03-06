<?php

namespace Tests\Feature;

use App\Http\Controllers\CourseEditionUserController;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CourseEditionUserTest extends TestCase
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
        CourseEditionUser::insert(
            [
                'id' => 2,
                'user_id' => 2,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
        Auth::shouldReceive('user')->andReturn(null);
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(2);
        $this->controller = new CourseEditionUserController();
    }

    /**
     * Test to verify CourseEditionUser view route.
     */
    public function testCourseEditionUserView()
    {
        $this->before();
        $response = $this->get('/studentList/1')
            ->assertSee('student')
            ->assertDontSee('lecturer');
        $response->assertStatus(200);
    }
}
