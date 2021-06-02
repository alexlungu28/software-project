<?php

namespace Tests\Unit;

use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class CourseEditionControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Test to verify that the correct view is returned upon accessing the route.
     */
    public function testCourseEditionCreate() {
        $response = $this->get('/courseEditionCreate/1');
        $response->assertViewIs('courseEditions.courseEdition_create');
    }

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(1);
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
            ]
        );
        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer'
            ]
        );
    }

    /**
     * Test to verify insertion inside the database.
     */
    public function testCourseEditionStore()
    {
        $this->before();
        $response = $this->post(
            '/courseEditionStore/1',
            [
                'course_id' => 1,
                'year' => 2021
            ],
        );
        $this->assertDatabaseHas(
            'course_editions',
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer'
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that no duplicate entries will be created in the database.
     */
    public function testCourseEditionStoreException()
    {
        $this->before();

        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        $response = $this->post(
            '/courseEditionStore/1',
            [
                'course_id' => 1,
                'year' => 2021
            ],
        );
        $this->expectOutputString("Course edition already exists.<br/>Redirecting you back to main page...");
    }

    /**
     * Test to verify that the correct view is returned upon accessing the route.
     */
    public function testCourseEditionEdit() {
        $response = $this->get('/courseEditionEdit/1');
        $response->assertViewIs('courseEditions.courseEdition_edit');
    }

    /**
     * Test to verify the rubric is updated inside the database.
     */
    public function testCourseEditionUpdate()
    {
        $this->before();
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        $this->assertDatabaseHas(
            'course_editions',
            [
                'course_id' => 1,
                'year' => 2021,
            ]
        );
        $response = $this->put(
            '/courseEditionUpdate/1',
            [
                'id' => 1,
                'course_id' => 1,
                'year' => 2022,
            ],
        );
        $this->assertDatabaseHas(
            'course_editions',
            [
                'id' => 1,
                'course_id' => 1,
                'year' => 2022,
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that there will be no duplicate entries
     * when updating an entry in the database.
     */
    public function testCourseEditionUpdateException() {
        $this->before();
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2022
            ]
        );
        $response = $this->put(
            '/courseEditionUpdate/1',
            [
                'id' => 1,
                'year' => 2022
            ],
        );
        $this->expectOutputString("Course edition already exists.<br/>Redirecting you back to main page...");
    }

    /**
     * Test to verify that the correct view is returned upon accessing the route.
     */
    public function testCourseEditionDelete() {
        $response = $this->get('/courseEditionDelete/1');
        $response->assertViewIs('courseEditions.courseEdition_delete');
    }

    /**
     * Test to verify deletion inside the database.
     */
    public function testCourseEditionDestroy()
    {
        $this->before();
        CourseEdition::insert(
            [
                'course_id' => 1,
                'year' => 2021
            ]
        );
        $this->assertDatabaseHas(
            'course_editions',
            [
                'course_id' => 1,
                'year' => 2021,
            ]
        );
        $response = $this->delete(
            '/courseEditionDestroy',
            [
                'id' => 1,
            ],
        );
        $this->assertSoftDeleted(
            'course_editions',
            [
                'id' => 1,
            ],
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify that the correct view is returned according to user role.
     */
    public function testViewLecturer() {
        $this->before();
        $response = $this->get('/edition/1');
        $response->assertViewIs('groups.allgroups');
    }

    /**
     * Test to verify that the correct view is returned according to user role.
     */
    public function testViewTA() {
        Auth::shouldReceive('id')->twice()->andReturn(1);
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
        $response = $this->get('/edition/1');
        $response->assertViewIs('groups.groupTA');
    }

    /**
     * Test to verify that a user which does not have one of the permitted roles
     * to access the route is redirected to the unauthorized page.
     */
    public function testViewUnauthorized() {
        Auth::shouldReceive('id')->once()->andReturn(1);
        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student'
            ]
        );
        $response = $this->get('/edition/1');
        $response->assertRedirect('/unauthorized');
    }
}