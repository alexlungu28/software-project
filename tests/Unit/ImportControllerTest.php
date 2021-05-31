<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{

    use withoutMiddleware;

    /**
     * Test to verify ImportExport view route.
     */
    public function testImportExportView()
    {
        $response = $this->get('/importExportView/1');
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
     * Test to verify a user can export the list of students.
     *
     * @return void
     */
    public function testUserCanExportStudents()
    {

        Excel::fake();

        $response = $this->get(route('export', [1]));

        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }
}
