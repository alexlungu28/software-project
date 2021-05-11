<?php

namespace Tests\Feature;

use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ImportTest extends TestCase
{
    /**
     * Test to verify a user can import the list of students.
     *
     * @return void
     */
    public function testUserCanImportStudents()
    {
        $this->withoutMiddleware();

        $file = UploadedFile::fake()->create('ImportTest.csv');

        Excel::fake();

        $response = $this->post(route('import'), [
            'fileToUpload' => $file
        ]);
        $response->assertRedirect('');
    }
}
