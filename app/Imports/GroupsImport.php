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
        if (!Group::where('group_name', '=', $row['first_category'])->exists()) {
            return new Group([
                'group_name'    => $row['first_category'],
            ]);
        } else {
            return null;
        }
    }
}
