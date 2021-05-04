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
            'name'     => $row['username'],
            'email' => $row['email'],
            'user_role' => 'student',
        ]);
    }
}
