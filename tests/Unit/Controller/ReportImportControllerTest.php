<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\ReportImportController;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use PHPMD\Report;
use Tests\TestCase;

class ReportImportControllerTest extends TestCase
{
    use withoutMiddleware;
    use RefreshDatabase;

    public function testGitAnalysisImportStores()
    {
        $controller = new ReportImportController();
        $testinput = '{
        "gitinspector": {
            "version": "0.5.0dev",
                "repository": "OOPP",
                "report_date": "2021/06/03",
                "changes": {
                "message": "The following historical commit information, by author, was found",
                        "authors": [
                        {
                            "name": "Firstname1 Surname1",
                                "email": "f1.surname1@student.tudelft.nl",
                                "commits": 39,
                                "insertions": 2392,
                                "deletions": 555,
                                "percentage_of_changes": 10.99
                        },{
                                "name": "F2Surname2",
                                "email": "F2.Surname2@student.tudelft.nl",
                                "commits": 47,
                                "insertions": 3273,
                                "deletions": 788,
                                "percentage_of_changes": 15.14
                        }]
                },
                "blame": {
                    "authors": [
                        {
                                "name": "Firstname1 Surname1",
                                "email": "f1.surname1@student.tudelft.nl",
                                "rows": 1828,
                                "stability": 76.4,
                                "age": 1.7,
                                "percentage_in_comments": 10.01
                        }]
                    },
                "timeline": {
                    "periods": [
                        {
                            "name": "2020W10",
                                "authors": [
                                {
                                        "name": "F2Surname2",
                                        "email": "F2.Surname2@student.tudelft.nl",
                                        "work": "++"
                                }]
                            }]
                        }
                    }
                }';
        $parsedInput = json_decode($testinput);
        $controller->saveGitAnalysis($parsedInput, 1, 1);
        $this->assertDatabaseHas(
            'gitanalyses',
            [
                'id' => 1,
                'group_id' => 1,
                'week_number' => 1,
                'names' => '["Firstname1 Surname1","F2Surname2"]',
                'emails' => '["f1.surname1@student.tudelft.nl","f2.surname2@student.tudelft.nl"]',
                'activity' => '[{"commits":39,"insertions":2392,"deletions":555,"percentage_of_changes":10.99},'
                . '{"commits":47,"insertions":3273,"deletions":788,"percentage_of_changes":15.14}]',
                'blame' => '[{"rows":1828,"stability":76.4,"age":1.7,"percentage_in_comments":10.01},null]',
                'timeline' => '[".","++"]'
            ]
        );
    }

    public function testBuddycheckImportStores()
    {
        $controller = new ReportImportController();
        User::insert([
            'org_defined_id' => 'student',
            'net_id' => 'testStudent@tudelft.nl1',
            'last_name' => 'dent',
            'first_name' => 'stu',
            'email' => 'testStudent@student.tudelft.nl1',
            'affiliation' => 'student'
        ]);
        Group::insert([
            'group_name' => 'Group 1',
            'course_edition_id' => 1,
        ]);
        GroupUser::insert([
            'user_id' => 1,
            'group_id' => 1,
        ]);
        $testInput = [
            '{"Email":"testStudent@tudelft.nl1","Avg with self":"4.43","Adj factor with self":"1.05"}'
        ];
        $controller->saveBuddycheck($testInput, 1, 1);
        $this->assertDatabaseHas(
            'buddychecks',
            [
                'user_id' => 1,
                'group_id' => 1,
                'week' => 1,
                'data' => '{"Email":"testStudent@tudelft.nl1","Avg with self":"4.43","Adj factor with self":"1.05"}'
            ]
        );
    }
}
