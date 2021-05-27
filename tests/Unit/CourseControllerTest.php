<?php

namespace Tests\Unit;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

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
