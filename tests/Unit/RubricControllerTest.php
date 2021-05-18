<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RubricControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    public function testRubricStore()
    {
        $response = $this->post(
            '/rubricStore',
            ['name' => 'TestName'],
        );
        $this->assertDatabaseHas('rubrics', [
            'name' => 'TestName'
        ]);
        $response->assertStatus(200);
    }

    public function testRubricUpdate()
    {
        DB::table('rubrics')->insert([
            'name' => 'TestName'
        ]);
        $this->assertDatabaseHas('rubrics', [
            'name' => 'TestName'
        ]);
        $response = $this->post(
            '/rubricUpdate',
            [
                'id' => 1,
                'name' => 'NewName',
            ],
        );
        $this->assertDatabaseHas('rubrics', [
            'id' => 1,
            'name' => 'NewName',
        ]);
        $response->assertStatus(200);
    }

    public function testRubricDestroy()
    {
        DB::table('rubrics')->insert(
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
