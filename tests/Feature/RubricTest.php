<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class RubricTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    public function testCreateRubric()
    {
        $response = $this->get('/rubricCreate')->assertSee('Rubric Management | Add');
        $response->assertStatus(200);
    }

    public function testEditRubric()
    {
        $response = $this->get('/rubricEdit')->assertSee('Rubric Management | Update');
        $response->assertStatus(200);
    }

    public function testDeleteRubric()
    {
        $response = $this->get('/rubricDelete')->assertSee('Rubric Management | Delete');
        $response->assertStatus(200);
    }

    public function testViewRubrics()
    {
        DB::table('rubrics')->insert([
            'name' => 'TestName'
        ]);
        $response = $this->get('viewRubrics')->assertSee('TestName');
        $response->assertStatus(200);
    }
}
