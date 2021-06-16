<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\ReportImportController;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use PHPMD\Report;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{

    use withoutMiddleware;

    /**
     * Test to verify Import view route.
     */
    public function testImportView()
    {
        $response = $this->get('/importView/1');
        $response->assertStatus(200);
    }


    /**
     * Test to verify a user can import the list of students.
     *
     * @return void
     */
    public function testUserCanImportStudents()
    {
        $file = UploadedFile::fake()->create('ImportTest.csv');

        Excel::fake();

        $response = $this->post(route('import', [1]), [
            'fileToUpload' => $file
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('');
    }

    /**
     * Test to verify a user can import the list of TAs.
     *
     * @return void
     */
    public function testUserCanImportTAs()
    {
        $file = UploadedFile::fake()->create('ImportTest.csv');

        Excel::fake();

        $response = $this->post(route('importTA', [1]), [
            'fileToUpload' => $file
        ]);

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('');
    }
}
