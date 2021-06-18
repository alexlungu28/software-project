<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportImportController extends Controller
{
    /**
     * Takes the file from the request and parses it into json for saveGitAnalysis to insert into the database.
     *
     * @param $groupId
     * @param $week
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function importGitanalysis($groupId, $week)
    {
        // Get the text file and write the contents in a string
        $jsonFile = request()->file('file');
        $jsonData = file_get_contents($jsonFile->getPathname());
        // If it failed print an error and return
        if ($jsonData == false) {
            echo "Error reading file";
            return null;
        }
        // Else decode the json and check for success
        $parsedData = json_decode($jsonData);
        if ($parsedData == null) {
            echo "Error parsing JSON";
            return null;
        }
        // Send the parsed json to the save method
        $this->saveGitAnalysis($parsedData, $groupId, $week);
        return back();
    }

    /**
     * Takes the json structure from importGitAnalysis and takes each section of data to store it in the database.
     *
     * @param $parsedData
     * @param $groupId
     * @param $week
     */
    public function saveGitAnalysis($parsedData, $groupId, $week)
    {
        try {
            // In the json structure with a defined structure, get the gitinspector field.
            $gitinspector = $parsedData->gitinspector;
            // From the gitinspector field we take the authors and their activity
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
            // We take the blame for each author
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
            // And we take the timeline for each author
            $timeline = end($gitinspector->timeline->periods);
            $count = 0;
            $timelineData = array_fill(0, count($authors), null);
            foreach ($timeline->authors as $author) {
                // If the author is not present in the timeline their entry is filled with '.'
                while ($author->name != $namesData[$count]) {
                    $timelineData[$count] = ".";
                    $count++;
                }
                $timelineData[$count] = $author->work;
                $count++;
            }
            // Save the gitanalysis for the entire group
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

    /**
     * Takes the file from the request and parses it into json for saveBuddycheck to insert into the database.
     *
     * @param $groupId
     * @param $week
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importBuddycheck($groupId, $week)
    {
        // Retrieve the file from the request
        $csvFile = request()->file('file');
        // Get read access to the file
        $fileHandle = fopen($csvFile->getPathname(), 'r');
        // If reading the file failed return back.
        if ($fileHandle == false) {
            echo "Error reading file";
            return back();
        }
        // Else use fgetcsv to get the headers from the file
        $csvHeaders = fgetcsv($fileHandle, 0, ';');
        $csvJson = array();

        // Store each row in the csv as a json_encoded string in the array
        while ($row = fgetcsv($fileHandle, 0, ';')) {
            $csvJson[] = json_encode(array_combine($csvHeaders, $row));
        }

        // Close the file and pass the data to the save method
        fclose($fileHandle);
        $this->saveBuddycheck($csvJson, $groupId, $week);
        return back();
    }

    /**
     * Takes the json structure from importBuddycheck and links each data segment in it to students from the group.
     *
     * @param $parsedData, the data parsed in importBuddyCheck.
     * @param $groupId, the id of the group to which the buddycheck is being uploaded.
     * @param $week, the week in which the buddycheck is being uploaded.
     */
    public function saveBuddycheck($parsedData, $groupId, $week)
    {
        try {
            // Get all users in the group
            $users = Group::find($groupId)->users;
            foreach ($parsedData as $row) {
                // For every entry in the array get the student it belongs to
                $jsonRow = json_decode($row);
                // If this fails the try catch is broken and the rest of the csv is not stored
                $student = User::where('net_id', '=', $jsonRow->Email)->first();
                // If the user is part of the group, store their buddycheck results
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
