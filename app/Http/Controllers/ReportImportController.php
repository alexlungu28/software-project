<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportImportController extends Controller
{
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
        $this->saveGitAnalysis($parsedData, $groupId, $week);
        return back();
    }

    public function saveGitAnalysis($parsedData, $groupId, $week)
    {
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
        } catch (Error $error) {
            echo "Error retrieving data from parsed JSON, make sure gitanalysis was run with the Grading setting";
        }
    }

    public function importBuddycheck($groupId, $week)
    {
        $csvFile = request()->file('file');
        $fileHandle = fopen($csvFile->getPathname(), 'r');
        if ($fileHandle == false) {
            echo "Error reading file";
            return back();
        }
        $csvHeaders = fgetcsv($fileHandle, 0, ';');
        $csvJson = array();

        while ($row = fgetcsv($fileHandle, 0, ';')) {
            $csvJson[] = json_encode(array_combine($csvHeaders, $row));
        }

        fclose($fileHandle);
        $this->saveBuddycheck($csvJson, $groupId, $week);
        return back();
    }

    public function saveBuddycheck($parsedData, $groupId, $week)
    {
        try {
            $users = Group::find($groupId)->users;

            foreach ($parsedData as $row) {
                $jsonRow = json_decode($row);
                $student = User::where('net_id', '=', $jsonRow->Email)->first();
                if ($users->contains($student)) {
                    DB::table('buddychecks')->updateOrInsert(
                        [
                            'user_id' => $student->id,
                            'group_id' => $groupId,
                            'week' => $week,
                        ],
                        [
                            'user_id' => $student->id,
                            'group_id' => $groupId,
                            'week' => $week,
                            'data' => $row,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        } catch (Error $error) {
            echo "Error retrieving data from parsed csv\n"
                . "Make sure all rows in the csv correspond to a student in this group";
        }
    }
}
