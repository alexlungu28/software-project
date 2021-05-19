<?php

namespace Tests\Feature;

use App\Models\Rubric;
use App\Models\RubricEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class RubricEntryTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    public function before()
    {
        Rubric::insert(
            [
                'name' => 'TestName'
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

    public function testRubricCreate()
    {
        $this->before();
        $response = $this->get('/rubricEntryCreate')->assertSee('Rubric Entry Management | Add');
        $response->assertStatus(200);
    }

    public function testRubricEdit()
    {
        $this->before();
        $response = $this->get('/rubricEntryEdit/1/0')
            ->assertSee('Rubric Entry Management | Update')
            ->assertSee('Column 1');
        $response->assertStatus(200);
    }

    public function testRubricView()
    {
        $this->before();
        $response = $this->get('/viewRubric/1')
            ->assertSee('TestName')
            ->assertSee('Column 1');
        $response->assertStatus(200);
    }
}
