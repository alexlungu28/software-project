<?php

namespace App\Imports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return Group
     */
    public function model(array $row)
    {
        //position before the beginning of categories
        $position_email = array_search('email', array_keys($row));

        //position right after the end of categories
        $position_grade = array_search('group_text_grade_text', array_keys($row));

        $group_name = null;
        for ($x = $position_email + 1; $x < $position_grade; $x++) {
            //finds the group name by iterating over all categories
            if ($row[array_keys($row)[$x]] !=null) {
                $group_name = $row[array_keys($row)[$x]];
            }
        }
        if (!Group::where('group_name', '=', $group_name)->exists()) {
            return new Group([
                'group_name'    => $group_name,
            ]);
        } else {
            return null;
        }
    }
}
