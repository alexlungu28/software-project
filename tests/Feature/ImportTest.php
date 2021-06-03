<?php

namespace Tests\Feature;

use App\Imports\CourseEditionTAImport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ImportTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * @var CourseEditionTAImport
     */
    private $cEditionTaImport;

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
        $this->cEditionTaImport = new CourseEditionTAImport(1);

        User::insert(
            [
                'id' => 1,
                'org_defined_id' => 5104444,
                'net_id' => 'testnetid',
                'last_name' => 'testlastname',
                'first_name' => 'testfirstname',
                'email' => 'testnetid@test.test',
                'affiliation' => 'student',
            ]
        );
    }

    /**
     * Test to verify CourseEditionTAImport model function.
     */
    public function testModel()
    {
        $this->before();
        $row = ([
            'orgdefinedid' => 5104444,
        ]);

        $this->cEditionTaImport->model($row)->save();
        $this->assertDatabaseHas(
            'course_edition_user',
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'TA',
            ]
       );

        $this->assertNull($this->cEditionTaImport->model($row));
    }

}
