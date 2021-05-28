<?php

namespace Tests\Unit;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CourseEditionControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
        Course::insert(
            [
                'course_number' => 'CSE1234',
                'description' => 'OOP'
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
                'course_id' => '1',
                'year' => '2021'
            ],
        );
        $this->assertDatabaseHas(
            'course_editions',
            [
                'course_id' => '1',
                'year' => '2021'
            ]
        );
        $response->assertStatus(302);
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
}
