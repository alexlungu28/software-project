<?php

namespace Tests\Unit\Controller;

use App\Http\Controllers\ReportImportController;
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
                                        "name": "Firstname1 Surname1",
                                        "email": "f1.surname1@student.tudelft.nl",
                                        "work": "++++++"
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
                'names' => '["Firstname1 Surname1"]',
                'emails' => '["f1.surname1@student.tudelft.nl"]',
                'activity' => '[{"commits":39,"insertions":2392,"deletions":555,"percentage_of_changes":10.99}]',
                'blame' => '[{"rows":1828,"stability":76.4,"age":1.7,"percentage_in_comments":10.01}]',
                'timeline' => '["++++++"]'
            ]
        );
    }
}
