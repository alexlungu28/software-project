<?php

namespace App\Imports;

use App\Models\GroupUser;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupUserImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return GroupUser
     */
    public function model(array $row)
    {
        return new GroupUser([
            'user_id'    => trim($row['orgdefinedid'], "#"),
            'group_name'    => $row['first_category'],
        ]);
    }
}
