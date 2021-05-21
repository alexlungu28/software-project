<?php

namespace App\Imports;

use App\Models\CourseEditionUser;
use App\Models\Group;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseEditionUserImport implements ToModel, WithHeadingRow
{
    private $editionId;

    public function __construct(int $editionId)
    {
        $this->editionId = $editionId;
    }

    /**
    * @param array $row
    *
    * @return CourseEditionUser
     */
    public function model(array $row)
    {

        $userId = User::select('id')->where('org_defined_id', '=', trim($row['orgdefinedid'], "#"))->first()->id;

        return new CourseEditionUser([
            'user_id'    => $userId,
            'course_edition_id' => $this->editionId,
            'role' => 'student',
        ]);
    }
}
