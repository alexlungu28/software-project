<?php

namespace Tests\Unit\Controller;

use App\Models\CourseEditionUser;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportControllerTest extends TestCase
{

    use withoutMiddleware;
    use RefreshDatabase;

    public function before() {
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
     * Test to verify Export view route.
     */
    public function testExportView()
    {
        $this->before();
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

    /**
     * Test to verify a user can export rubrics.
     *
     * @return void
     */
    public function testUserCanExportRubrics()
    {
        Excel::fake();
        $response = $this->get(route('exportRubrics', [1]));
        Excel::assertDownloaded('rubrics.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }

    /**
     * Test to verify a user can export group notes.
     *
     * @return void
     */
    public function testUserCanExportGroupNotes()
    {
        Excel::fake();
        $response = $this->get(route('exportGroupNotes', [1]));
        Excel::assertDownloaded('group_notes.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }

    /**
     * Test to verify a user can export individual notes.
     *
     * @return void
     */
    public function testUserCanExportIndividualNotes()
    {
        Excel::fake();
        $response = $this->get(route('exportIndividualNotes', [1]));
        Excel::assertDownloaded('individual_notes.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }

    /**
     * Test to verify a user can export group interventions.
     *
     * @return void
     */
    public function testUserCanExportGroupInterventions()
    {
        Excel::fake();
        $response = $this->get(route('exportGroupInterventions', [1]));
        Excel::assertDownloaded('group_interventions.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }

    /**
     * Test to verify a user can export individual interventions.
     *
     * @return void
     */
    public function testUserCanExportIndividualInterventions()
    {
        Excel::fake();
        $response = $this->get(route('exportIndividualInterventions', [1]));
        Excel::assertDownloaded('individual_interventions.csv');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertOk();
    }
}
