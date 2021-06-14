<?php

namespace App\Http\Controllers;

use App\Imports\CourseEditionTAImport;
use App\Imports\CourseEditionStudentImport;
use App\Imports\GroupsImport;
use App\Imports\GroupsTAImport;
use App\Imports\GroupUserImport;
use App\Imports\GroupUserTAImport;
use App\Models\Gitanalysis;
use Error;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\Imports\UsersImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{
    /**
     * View of the import page.
     *
     * @return Application|Factory|View
     */
    public function importView($editionId)
    {
        return view('import', [
            "edition_id" => $editionId,
        ]);
    }

    /**
     * Imports users inside the users table,
     * groups inside the groups table,
     * the link between the former two inside the group_user table,
     * the role inside the course_edition_user table.
     *
     * @param $editionId - the course edition where the students will be imported
     * @return RedirectResponse - returns the user back to the import page
     */
    public function import($editionId): RedirectResponse
    {
        Excel::import(new UsersImport, request()->file('file'));
        Excel::import(new GroupsImport($editionId), request()->file('file'));
        Excel::import(new GroupUserImport($editionId), request()->file('file'));
        Excel::import(new CourseEditionStudentImport($editionId), request()->file('file'));
        return back();
    }

    /**
     * Imports TAs inside the users table,
     * the role inside the course_edition_user table.
     *
     * @param $editionId - the course edition where the TAs will be imported
     * @return RedirectResponse - returns the user back to the import page
     */
    public function importTA($editionId): RedirectResponse
    {
        Excel::import(new UsersImport, request()->file('file'));
        Excel::import(new GroupsTAImport($editionId), request()->file('file'));
        Excel::import(new GroupUserTAImport($editionId), request()->file('file'));
        Excel::import(new CourseEditionTAImport($editionId), request()->file('file'));
        return back();
    }

    public function importGitanalysis($groupId, $week)
    {
        $jsonFile = request()->file('file');
        $jsonData = file_get_contents($jsonFile->getPathname());
        if ($jsonData == false) {
            echo "Error reading file";
            return null;
        }
        $parsedData = json_decode($jsonData);
        if ($parsedData == null) {
            echo "Error parsing JSON";
            return null;
        }
        try {
            $gitinspector = $parsedData->gitinspector;
            $authors = $gitinspector->changes->authors;
            $namesData = array_fill(0, count($authors), null);
            $emailsData = array_fill(0, count($authors), null);
            $activityData = array_fill(0, count($authors), null);
            foreach ($authors as $key => $author) {
                $namesData[$key] = $author->name;
                $emailsData[$key] = strtolower($author->email);
                $activityData[$key] = array(
                    'commits' => $author->commits,
                    'insertions' => $author->insertions,
                    'deletions' => $author->deletions,
                    'percentage_of_changes' => $author->{'percentage_of_changes'},
                );
            }
            $blames = $gitinspector->blame->authors;
            $blameData = array_fill(0, count($authors), null);
            foreach ($blames as $key => $blame) {
                $blameData[$key] = array(
                    'rows' => $blame->rows,
                    'stability' => $blame->stability,
                    'age' => $blame->age,
                    'percentage_in_comments' => $blame->{'percentage_in_comments'}
                );
            }
            $timeline = end($gitinspector->timeline->periods);
            $count = 0;
            $timelineData = array_fill(0, count($authors), null);
            foreach ($timeline->authors as $author) {
                while ($author->name != $namesData[$count]) {
                    $timelineData[$count] = ".";
                    $count++;
                }
                $timelineData[$count] = $author->work;
                $count++;
            }
            DB::table('gitanalyses')->updateOrInsert(
                [
                    'group_id' => $groupId,
                    'week_number' => $week,
                ],
                [
                    'names' => json_encode($namesData),
                    'emails' => json_encode($emailsData),
                    'activity' => json_encode($activityData),
                    'blame' => json_encode($blameData),
                    'timeline' => json_encode($timelineData),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
            return back();
        } catch (Error $error) {
            echo "Error retrieving data from parsed JSON, make sure gitanalysis was run with the Grading setting";
        }
    }
}
