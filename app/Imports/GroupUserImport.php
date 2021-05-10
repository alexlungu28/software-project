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
        if (!GroupUser::where('user_id', '=', trim($row['orgdefinedid'], "#"))->exists() &&
            !GroupUser::where('group_name', '=', $row['first_category'])->exists()) {
            return new GroupUser([
                'user_id' => trim($row['orgdefinedid'], "#"),
                'group_name' => $row['first_category'],
            ]);
        } else {
            return null;
        }
    }
}
