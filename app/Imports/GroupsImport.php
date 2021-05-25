<?php

namespace App\Imports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupsImport implements ToModel, WithHeadingRow
{
    private $editionId;

    /**
     * GroupsImport constructor.
     * @param int $editionId
     */
    public function __construct(int $editionId)
    {
        $this->editionId = $editionId;
    }

    /**
     * Adds a group in the database.
     *
     * @param array $row
     *
     * @return Group
     */
    public function model(array $row)
    {
        //position before the beginning of categories
        $positionEmail = array_search('email', array_keys($row));

        //position right after the end of categories
        $positionGrade = array_search('group_text_grade_text', array_keys($row));

        $groupName = null;
        for ($x = $positionEmail + 1; $x < $positionGrade; $x++) {
            //finds the group name by iterating over all categories
            if ($row[array_keys($row)[$x]] !=null) {
                $groupName = $row[array_keys($row)[$x]];
            }
        }
        if (!Group::where('group_name', '=', $groupName)->exists()) {
            return new Group([
                'group_name'    => $groupName,
                'course_edition_id' => $this->editionId,
            ]);
        } else {
            return null;
        }
    }
}
