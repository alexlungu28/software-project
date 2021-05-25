<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\Rubric;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
    }

    /**
     * Test to verify rubric create route.
     */
    public function testRubricCreate()
    {
        $this->before();
        $response = $this->get('/rubricEntryCreate')->assertSee('Rubric Entry Management | Add');
        $response->assertStatus(200);
    }

    /**
     * Test to verify rubric edit route.
     */
    public function testRubricEdit()
    {
        $this->before();
        $response = $this->get('/rubricEntryEdit/1/0')
            ->assertSee('Rubric Entry Management | Update')
            ->assertSee('Column 1');
        $response->assertStatus(200);
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
}
