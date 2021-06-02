<?php

namespace Tests\Unit;

use App\Http\Controllers\CourseController;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class CourseControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Test to verify insertion inside the database.
     */
    public function testCourseStore()
    {
        $response = $this->post(
            '/courseStore',
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
            ],
        );
        $this->assertDatabaseHas(
            'courses',
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that no duplicate entries will be created in the database.
     */
    public function testCourseStoreException() {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        $response = $this->post(
            '/courseStore',
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
            ],
        );
        $this->expectOutputString("Course number already exists.<br/>Redirecting you back to main page...");
    }

    /**
     * Test to verify that all courses in the database are retrieved
     * when calling the getAllCourses static method.
     */
    public function testGetAllCourses() {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        Course::insert(
            [
                'course_number' => 'CSE4321',
                'description' => 'Software Project',
            ]
        );
        assertEquals(2, CourseController::getAllCourses()->count());
    }

    /**
     * Test to verify the rubric is updated inside the database.
     */
    public function testCourseUpdate()
    {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        $this->assertDatabaseHas(
            'courses',
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        $response = $this->put(
            '/courseUpdate',
            [
                'id' => 1,
                'course_number' => 'CSE4321',
                'description' => 'Software Project',
            ],
        );
        $this->assertDatabaseHas(
            'courses',
            [
                'id' => 1,
                'course_number' => 'CSE4321',
                'description' => 'Software Project',
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that there will be no duplicate entries
     * when updating an entry in the database.
     */
    public function testCourseUpdateException() {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        Course::insert(
            [
                'course_number' => 'CSE4321',
                'description' => 'Software Project',
            ]
        );
        $response = $this->put(
            '/courseUpdate',
            [
                'id' => 2,
                'course_number' => 'CSE1234',
                'description' => 'Software Project'
            ],
        );
        $this->expectOutputString("Course number already exists.<br/>Redirecting you back to main page...");
    }

    /**
     * Test to verify deletion inside the database.
     */
    public function testCourseDestroy()
    {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        $this->assertDatabaseHas(
            'courses',
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP',
            ]
        );
        $response = $this->delete(
            '/courseDestroy',
            [
                'id' => 1,
            ],
        );
        $this->assertSoftDeleted(
            'courses',
            [
                'id' => 1,
            ],
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that the correct view is returned according to user affiliation.
     */
    public function testViewEmployee() {
        $user = new User([
            'affiliation' => 'employee'
        ]);
        Auth::shouldReceive('user')->once()->andReturn($user);
        $response = $this->get('/');
        $response->assertViewIs('courses.mainEmployee');
    }

    /**
     * Test to verify that the correct view is returned according to user affiliation.
     */
    public function testViewStudent() {
        $user = new User([
            'affiliation' => 'student'
        ]);
        Auth::shouldReceive('user')->once()->andReturn($user);
        Auth::shouldReceive('id')->twice()->andReturn(1);
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
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
                'content' => 'First group',
                'grade' => 10,
                'course_edition_id' => 1
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
        $response = $this->get('/');
        $response->assertViewIs('courses.mainStudent');
    }

    /**
     * Test to verify that the correct view is returned according to user affiliation.
     */
    public function testViewEmployeeCE() {
        $user = new User([
            'affiliation' => 'employee'
        ]);
        Auth::shouldReceive('user')->once()->andReturn($user);
        $response = $this->get('/courses/1');
        $response->assertViewIs('courseEditions.courseEditionEmployee');
    }

    /**
     * Test to verify that the correct view is returned according to user affiliation.
     */
    public function testViewStudentCE() {
        $user = new User([
            'affiliation' => 'student'
        ]);
        Auth::shouldReceive('user')->once()->andReturn($user);
        Auth::shouldReceive('id')->once()->andReturn(1);
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
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
                'content' => 'First group',
                'grade' => 10,
                'course_edition_id' => 1
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
        CourseEditionUser::insert(
            [
                'user_id' => 2,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );
        $response = $this->get('/courses/1');
        $response->assertViewIs('courseEditions.courseEditionStudent');
    }
}
