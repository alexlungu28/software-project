<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Rubric;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RubricEntryTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 0,
                'description' => 'Column 1',
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
    }

    /**
     * Test to verify rubric view route.
     */
    public function testRubricView()
    {
        $this->before();
        $response = $this->get('/viewRubricTA/1/1')
            ->assertSee('TestName')
            ->assertSee('Column 1');
        $response->assertStatus(200);
    }

    public function testTeacherView()
    {
        $this->before();
        $response = $this->get('/viewRubricTeacher/1/1')
            ->assertSeeInOrder(
                [
                    'TestName',
                    'Column 1',
                    'Update Column',
                    'Delete Column',
                    'Add a new Rubric Entry',
                    'Restore deleted Rubric Entry',
                ]
            );
        $response->assertStatus(200);
    }
}
