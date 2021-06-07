<?php

namespace App\Imports;

use App\Models\CourseEditionUser;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseEditionStudentImport implements ToModel, WithHeadingRow
{
    private $editionId;

    /**
     * CourseEditionStudentImport constructor.
     * @param int $editionId
     */
    public function __construct(int $editionId)
    {
        $this->editionId = $editionId;
    }

    /**
     * Adds a user role in the database.
     *
     * @param array $row
     *
     * @return CourseEditionUser
     */
    public function model(array $row)
    {

        $userId = User::select('id')->where('org_defined_id', '=', trim($row['orgdefinedid'], "#"))->first()->id;
        if (!CourseEditionUser::where('user_id', '=', $userId)
            ->where('course_edition_id', '=', $this->editionId)->exists()) {
            return new CourseEditionUser([
            'user_id'    => $userId,
            'course_edition_id' => $this->editionId,
            'role' => 'student',
            ]);
        } else {
            return null;
        }
    }
}
