<?php

namespace App\Imports;

use App\User;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return User
     */
    public function model(array $row)
    {
        return new User([
            'name'     => $row['name'],
            'org_defined_id'    => \Hash::make($row['org_defined_id']),
            'email' => $row['email'],
        ]);
    }
}
