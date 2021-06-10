<?php

namespace Tests\Unit;

use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RubricControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Test to verify insertion inside the database.
     */
    public function testRubricStore()
    {
        $response = $this->post(
            '/rubricStore',
            [
                'name' => 'TestName',
                'edition' => 1,
                'week' => 1,
            ],
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify the rubric is updated inside the database.
     */
    public function testRubricUpdate()
    {
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $response = $this->put(
            '/rubricUpdate',
            [
                'id' => 1,
                'name' => 'NewName',
                'week' => 2,
            ],
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'id' => 1,
                'name' => 'NewName',
                'week' => 2,
            ]
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify deletion inside the database.
     */
    public function testRubricDestroy()
    {
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $response = $this->delete(
            '/rubricDestroy',
            [
                'id' => 1,
            ],
        );
        $this->assertSoftDeleted(
            'rubrics',
            [
                'id' => 1,
            ],
        );
        $response->assertStatus(302);
    }

    /**
     * Test to verify restoring inside the database.
     */
    public function testRubricRestore()
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
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        RubricData::insert(
            [
                'rubric_id' => 1,
                'row_number' => 1,
                'group_id' => 1,
                'value' => 1,
                'note' => "This is also a note",
                'user_id' => 1,
            ]
        );
        Rubric::find(1)->delete();
        $this->assertSoftDeleted(
            'rubric_data',
            [
                'rubric_id' => 1,
                'row_number' => 1,
                'group_id' => 1,
                'value' => 1,
                'note' => "This is also a note",
                'user_id' => 1,
            ]
        );
        $response = $this->put(
            '/rubricRestore',
            [
                'id' => 1,
            ]
        );

        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        $this->assertDatabaseHas(
            'rubric_data',
            [
                'rubric_id' => 1,
                'row_number' => 1,
                'group_id' => 1,
                'value' => 1,
                'note' => "This is also a note",
                'user_id' => 1,
            ]
        );
        $response->assertStatus(302);
    }
}
