<?php

namespace Tests\Unit;

use App\Models\Rubric;
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
}
