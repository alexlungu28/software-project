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
        foreach ($row as $key => $elem) {
            echo $key . " " . $elem . "\n";
        }
        return new User([
            'org_defined_id'    => $row['orgdefinedid'],
            'net_id'     => $row['username'],
            'last_name'  => $row['last_name'],
            'first_name'  => $row['first_name'],
            'email' => $row['email'],
            'group' => $row['first_category'],
            'user_role' => 'student',
        ]);
    }
}
