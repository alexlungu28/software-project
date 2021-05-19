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

    public function testRubricStore()
    {
        $response = $this->post(
            '/rubricStore',
            [
                'name' => 'TestName'
            ],
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName'
            ]
        );
        $response->assertStatus(200);
    }

    public function testRubricUpdate()
    {
        Rubric::insert(
            [
                'name' => 'TestName'
            ]
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName'
            ]
        );
        $response = $this->post(
            '/rubricUpdate',
            [
                'id' => 1,
                'name' => 'NewName',
            ],
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'id' => 1,
                'name' => 'NewName',
            ]
        );
        $response->assertStatus(200);
    }

    public function testRubricDestroy()
    {
        Rubric::insert(
            [
                'name' => 'TestName'
            ]
        );
        $this->assertDatabaseHas(
            'rubrics',
            [
                'name' => 'TestName'
            ]
        );
        $response = $this->post(
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
        $response->assertStatus(200);
    }
}
