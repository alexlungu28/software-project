<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return User
     */
    public function model(array $row)
    {
        return new User([
            'org_defined_id'    => trim($row['orgdefinedid'], "#"),
            'net_id'     => trim($row['username'], "#"),
            'last_name'  => $row['last_name'],
            'first_name'  => $row['first_name'],
            'email' => $row['email'],
            'affiliation' => 'student',
        ]);
    }
}
