<?php

namespace Tests\Feature;

use App\Exports\GradesExport;
use App\Exports\UsersExport;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class ExportTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    /**
     * Insert the specified entries inside the database tables.
     */
    public function before()
    {
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

        Group::insert(
            [
                'group_name' => 'Group 1',
                'course_edition_id' => 1,
                'grade' => 1,
            ]
        );

        GroupUser::insert(
            [
                'user_id' => 1,
                'group_id' => 1,
                'student_grade' => 5,
            ]
        );

        Course::insert(
            [
                'id' => 1,
                'course_number' => 'CSE1234',
                'description' => 'Software Project',
            ]
        );
        CourseEdition::insert(
            [
                'id' => 1,
                'course_id' => 1,
                'year' => 2021,
            ]
        );
        CourseEditionUser::insert(
            [
                'id' => 1,
                'user_id' => 1,
                'course_edition_id' => 1,
                'role' => 'student',
            ]
        );
    }

    /**
     * Test to verify UsersExport class.
     */
    public function testUserExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportUserList', [1]));

        Excel::assertDownloaded('user_list.csv', function(UsersExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'OrgDefinedId',
                'Username',
                'Last Name',
                'First Name',
                'Email',
                'Affiliation',
                'Role'
            ], $export->headings());
            return $export->collection()->contains('org_defined_id','=','5104444');
        });
    }


    /**
     * Test to verify GradesExport class.
     */
    public function testGradesExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportGrades', [1]));

        Excel::assertDownloaded('grades.csv', function(GradesExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'OrgDefinedId',
                'Username',
                'GroupGrade',
                'IndividualGrade',
            ], $export->headings());
            return $export->collection()->contains('student_grade','=','5');
        });
    }

}