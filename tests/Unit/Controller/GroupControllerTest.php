<?php

namespace Tests\Unit;

use App\Models\CourseEditionUser;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class GroupControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Insert the specified entries inside the database tables.
     */
    private function before() {
        Auth::shouldReceive('id')->andReturn(1);
        Auth::shouldReceive('check')->andReturn(true);
        Auth::shouldReceive('user')->andReturn(null);
        Group::insert(
            [
                'group_name' => 'Group 1',
                'content' => 'First group',
                'grade' => 10,
                'course_edition_id' => 1
            ]
        );
    }

//    /**
//     * Test to verify that the correct view is returned according to user role.
//     */
//    public function testView() {
//        $this->before();
//        CourseEditionUser::insert(
//            [
//                'user_id' => 1,
//                'course_edition_id' => 1,
//                'role' => 'lecturer'
//            ]
//        );
//        $response = $this->get('/group/1');
//        $response->assertViewIs('weeks');
//    }

    /**
     * Test to verify that the correct view is returned according to user role.
     */
    public function testViewWeekLecturer() {
        $this->before();
        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'lecturer'
            ]
        );
        $response = $this->get('/group/1/week/1');
        $response->assertViewIs('week');
    }

    /**
     * Test to verify that the correct view is returned according to user role.
     */
    public function testViewWeekTA() {
        $this->before();
        CourseEditionUser::insert(
            [
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'TA'
            ]
        );
        $response = $this->get('/group/1/week/1');
        $response->assertViewIs('weekTA');
    }
}
