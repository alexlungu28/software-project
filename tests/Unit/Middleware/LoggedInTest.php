<?php

namespace Tests\Unit\Middleware;

use App\Http\Middleware\LoggedIn;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LoggedInTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test to verify that logged in users can access routes
     * that have the LoggedIn middleware
     */
    public function testLoggedIn()
    {
        $user = new User();
        $this->actingAs($user);
        $middleware = new LoggedIn;
        $request = Request::create('/', 'GET');
        $response = $middleware->handle($request, function() {
            $this->assertAuthenticated();
        });
        $this->assertNull($response);
    }

    /**
     * Test to verify that users who are not logged in are
     * not able to access routes that have the LoggedIn middleware
     */
    public function testUnauthorized() {
        $middleware = new LoggedIn;
        $request = Request::create('/', 'GET', []);
        $middleware->handle($request, function() {
            $resp = $this->get('/');
            $resp->assertStatus(302);
        });
        $this->assertNull(Auth::user());
    }
}
