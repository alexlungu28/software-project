<?php

namespace Tests\Unit;

use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RubricDataControllerTest extends TestCase
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
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 1,
                'is_row' => 0,
                'description' => 'Column 2',
            ]
        );
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
                'rubric_id' => 1,
                'row_number' => 0,
                'value' => -1,
                'note' => null,
            ]
        );
    }

    /**
     * Test to verify insertion inside the database.
     */
    public function testRubricDataStore()
    {
        $this->before();
        $response = $this->post(
            '/rubricDataStore/1',
            [
                '0' => 1,
                'text0' => "This is a note",
            ]
        );
        $this->assertDatabaseHas(
            'rubric_data',
            [
                'id' => 1,
                'rubric_id' => 1,
                'row_number' => 0,
                'value' => 1,
                'note' => "This is a note",
            ]
        );
        $response->assertStatus(200);
    }
}
