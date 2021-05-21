<?php

namespace Tests\Feature;

use App\Models\Rubric;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
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
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
            ]
        );
        $response = $this->get('viewRubrics/1')->assertSee('TestName');
        $response->assertStatus(200);
    }
}
