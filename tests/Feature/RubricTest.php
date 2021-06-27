<?php

namespace Tests\Feature;

use App\Models\CourseEditionUser;
use App\Models\Rubric;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RubricTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    public function before()
    {
        CourseEditionUser::insert(
            [
                'id' => 2,
                'user_id' => 2,
                'course_edition_id' => 1,
                'role' => 'lecturer',
            ]
        );
        Auth::shouldReceive('user')->andReturn(null);
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('id')->andReturn(2);
    }

    /**
     * Test to verify rubric view route.
     */
    public function testViewRubrics()
    {
        $this->before();
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );

        $response = $this->get('viewRubrics/1')->assertSee('TestName');
        $response->assertStatus(200);
    }
}
