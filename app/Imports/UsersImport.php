<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
     * Add a user in the database.
     * @param array $row
     *
     * @return User
     */
    public function model(array $row)
    {
        if (!User::where('org_defined_id', '=', trim($row['orgdefinedid'], "#"))->exists()) {
            return new User([
                'org_defined_id' => trim($row['orgdefinedid'], "#"),
                'net_id' => trim(trim($row['username'], "#"), "@tudelft.nl"),
                'last_name' => $row['last_name'],
                'first_name' => $row['first_name'],
                'email' => $row['email'],
                'affiliation' => 'student',
            ]);
        } else {
            return null;
        }
    }
}
