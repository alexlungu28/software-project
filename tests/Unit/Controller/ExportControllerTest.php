<?php

namespace Tests\Unit\Controller;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{

    use withoutMiddleware;

    /**
     * Test to verify Export view route.
     */
    public function testExportView()
    {
        $response = $this->get('/exportView/1');
        $response->assertStatus(200);
    }

    /**
     * Test to verify a user can export the list of students.
     *
     * @return void
     */
    public function testUserCanExportUserList()
    {
        $response = $this->get(route('exportUserList', [1]));

        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }
}
