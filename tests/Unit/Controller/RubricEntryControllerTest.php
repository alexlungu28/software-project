<?php

namespace Tests\Unit;

use App\Http\Controllers\RubricEntryController;
use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RubricEntryControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    private $controller;

    /**
     * Insert the specified entries inside the database tables.
     */
    protected function before()
    {
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $this->controller = new RubricEntryController();
    }

    /**
     * Test the distance correctly increments.
     */
    public function testAutoIncrementDistance()
    {
        $this->before();
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 6,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        $this->assertEquals(
            7,
            $this->controller->autoIncrementDistance(1, 1),
            "The new distance should be the largest distance +1"
        );
        $this->assertEquals(
            0,
            $this->controller->autoIncrementDistance(1, 0),
            "The distance of the first row/column should be 0"
        );
    }

    /**
     * Test to verify insertion inside the database.
     */
    public function testRubricEntryStore()
    {
        $this->before();
        $response = $this->post(
            '/rubricEntryStore',
            [
                'rubric_id' => 1,
                'is_row' => 1,
                'description' => 'Row 1',
            ],
        );
        $this->assertDatabaseHas(
            'rubric_entries',
            [
                'id' => 1,
                'rubric_id' => 1,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify the rubric is updated inside the database.
     */
    public function testRubricEntryUpdate()
    {
        $this->before();
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        $response = $this->put(
            '/rubricEntryUpdate',
            [
                'id' => 1,
                'isRow' => 1,
                'distance' => 0,
                'description' => 'NewDescription',
            ],
        );
        $this->assertDatabaseHas(
            'rubric_entries',
            [
                'id' => 1,
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'NewDescription',
            ],
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify the rubricEntry is deleted from the database.
     */
    public function testRubricEntryDelete()
    {
        $this->before();
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        $response = $this->delete(
            '/rubricEntryDelete/1'
        );
        $this->assertSoftDeleted(
            'rubric_entries',
            [
                'id' => 1,
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ],
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify the deleted rubricEntry and it's data is restored
     */
    public function testRubricEntryRestore()
    {
        $this->before();
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        RubricData::insert(
            [
                'id' => 1,
                'rubric_id' => 1,
                'group_id' => 1,
                'row_number' => 0,
                'value' => 1,
                'note' => "This is a note",
                'user_id' => 1,
            ]
        );
        $this->delete(
            '/rubricEntryDelete/1'
        );
        $this->assertSoftDeleted(
            'rubric_data',
            [
                'id' => 1,
                'rubric_id' => 1,
                'group_id' => 1,
                'row_number' => 0,
                'value' => 1,
                'note' => "This is a note",
                'user_id' => 1,
            ]
        );
        $response = $this->put(
            '/rubricEntryRollback',
            [
                'id' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'rubric_entries',
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        $this->assertDatabaseHas(
            'rubric_data',
            [
                'id' => 1,
                'rubric_id' => 1,
                'group_id' => 1,
                'row_number' => 0,
                'value' => 1,
                'note' => "This is a note",
                'user_id' => 1,
            ]
        );
        $response->assertStatus(302);
    }
}
