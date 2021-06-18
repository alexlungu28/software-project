<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\ReportImportController;
use App\Models\CourseEditionUser;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use PHPMD\Report;
use Tests\TestCase;

class ImportControllerTest extends TestCase
{

    use withoutMiddleware;

    public function before()
    {
        CourseEditionUser::insert(
            [
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
     * Test to verify Import view route.
     */
    public function testImportView()
    {
        $this->before();
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
