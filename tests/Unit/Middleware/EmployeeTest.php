<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\Employee;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class EmployeeTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify that employees can access routes
     * that have the Employee middleware
     */
    public function testEmployee()
    {
        $user = new User([
            'affiliation' => 'employee'
        ]);
        $this->actingAs($user);
        $middleware = new Employee;
        $request = Request::create('/courseStore', 'POST', [
            'course_number' => 'CSE1234',
            'description' => 'OOP'
        ]);
        $response = $middleware->handle($request, function() {
            $this->assertAuthenticated();
        });
        $this->assertNull($response);
    }

    /**
     * Test to verify that users who have a different affiliation are
     * not able to access routes that have the Employee middleware
     */
    public function testUnauthorized() {
        $user = new User([
            'affiliation' => 'student'
        ]);
        $this->actingAs($user);
        $middleware = new Employee;
        $request = Request::create('/courseStore', 'POST', [
            'course_number' => 'CSE1234',
            'description' => 'OOP'
        ]);
        $this->expectException(HttpException::class);
        $middleware->handle($request, function() {});
    }
}
