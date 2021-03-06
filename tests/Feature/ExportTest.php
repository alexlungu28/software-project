<?php

namespace Tests\Feature;

use App\Exports\GradesExport;
use App\Exports\GroupInterventionsExport;
use App\Exports\GroupNotesExport;
use App\Exports\IndividualInterventionsExport;
use App\Exports\IndividualNotesExport;
use App\Exports\RubricsExport;
use App\Exports\UsersExport;
use App\Models\Course;
use App\Models\CourseEdition;
use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\Intervention;
use App\Models\InterventionGroup;
use App\Models\Note;
use App\Models\NoteGroup;
use App\Models\Rubric;
use App\Models\RubricData;
use App\Models\RubricEntry;
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
        Rubric::insert(
            [
                'name' => 'TestName',
                'course_edition_id' => 1,
                'week' => 1,
            ]
        );
        RubricEntry::insert(
            [
                'rubric_id' => 1,
                'distance' => 0,
                'is_row' => 1,
                'description' => 'Row 1',
            ]
        );
        RubricData::insert(
            [
                'rubric_id' => 1,
                'group_id' => 1,
                'row_number' => 0,
                'value' => 2,
                'note' => 'student is good',
                'user_id' => 1
            ]
        );
        NoteGroup::insert(
            [
                'group_id' => 1,
                'week' => 1,
                'problem_signal' => 3,
                'note' => 'test'
            ]
        );
        Note::insert(
            [
                'user_id' => 1,
                'group_id' => 1,
                'week' => 1,
                'problem_signal' => 3,
                'note' => 'test'
            ]
        );
        Intervention::insert(
            [
                'user_id' => 1,
                'group_id' => 1,
                'reason' => 'note1',
                'start_day' => '2021-06-23',
                'end_day' => '2021-06-23',
                'status' => 1,
                'visible_ta' => 1
            ]
        );

        InterventionGroup::insert(
            [
                'group_id' => 1,
                'reason' => 'groupNote1',
                'start_day' => '2021-06-23',
                'end_day' => '2021-06-23',
                'status' => 1,
                'visible_ta' => 1
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

    /**
     * Test to verify RubricsExport class.
     */
    public function testRubricsExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportRubrics', [1]));

        Excel::assertDownloaded('rubrics.csv', function(RubricsExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'Group',
                'RubricName',
                'Week',
                'Description',
                'Value',
                'Note',
            ], $export->headings());
            return $export->collection()->contains('name','=','TestName');
        });
    }

    /**
     * Test to verify GroupNotesExport class.
     */
    public function testGroupNotesExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportGroupNotes', [1]));

        Excel::assertDownloaded('group_notes.csv', function(GroupNotesExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'Group',
                'Week',
                'ProblemSignal',
                'Note'
            ], $export->headings());
            return $export->collection()->contains('note','=','test');
        });
    }

    /**
     * Test to verify IndividualNotesExport class.
     */
    public function testIndividualNotesExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportIndividualNotes', [1]));

        Excel::assertDownloaded('individual_notes.csv', function(IndividualNotesExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'OrgDefinedId',
                'Username',
                'Last Name',
                'First Name',
                'Email',
                'Group',
                'Week',
                'ProblemSignal',
                'Note'
            ], $export->headings());
            return $export->collection()->contains('note','=','test');
        });
    }

    /**
     * Test to verify IndividualInterventionsExport class.
     */
    public function testIndividualInterventionsExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportIndividualInterventions', [1]));

        Excel::assertDownloaded('individual_interventions.csv', function(IndividualInterventionsExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'OrgDefinedId',
                'Username',
                'Last Name',
                'First Name',
                'Email',
                'Group',
                'Reason',
                'Action',
                'StartDate',
                'EndDate',
                'Status',
                'StatusNote',
                'TAVisibility'
            ], $export->headings());
            return $export->collection()->contains('reason','=','note1');
        });
    }

    /**
     * Test to verify GroupInterventionsExport class.
     */
    public function testGroupInterventionsExport()
    {

        $this->before();

        Excel::fake();

        $this->get(route('exportGroupInterventions', [1]));

        Excel::assertDownloaded('group_interventions.csv', function(GroupInterventionsExport $export) {
            // Assert that the correct export is downloaded.
            $this->assertEquals([
                'Group',
                'Reason',
                'Action',
                'StartDate',
                'EndDate',
                'Status',
                'StatusNote',
                'TAVisibility'
            ], $export->headings());
            return $export->collection()->contains('reason','=','groupNote1');
        });
    }

}
