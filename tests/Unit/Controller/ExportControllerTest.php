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
        Excel::fake();
        $response = $this->get(route('exportUserList', [1]));
        Excel::assertDownloaded('user_list.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }

    /**
     * Test to verify a user can export grades.
     *
     * @return void
     */
    public function testUserCanExportGrades()
    {
        Excel::fake();
        $response = $this->get(route('exportGrades', [1]));
        Excel::assertDownloaded('grades.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }
}
