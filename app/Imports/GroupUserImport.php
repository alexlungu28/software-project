<?php

namespace App\Imports;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupUserImport implements ToModel, WithHeadingRow
{

    private $editionId;

    /**
     * GroupsImport constructor.
     * @param int $editionId
     */
    public function __construct(int $editionId)
    {
        $this->editionId = $editionId;
    }

    /**
     * Adds a link between groups and users in the database.
     *
     * @param array $row
     *
     * @return GroupUser
     */
    public function model(array $row)
    {
        //position before the beginning of categories
        $positionEmail = array_search('email', array_keys($row));

        //position right after the end of categories
        $positionGrade = array_search('group_text_grade_text', array_keys($row));

        $groupName = null;
        for ($x = $positionEmail + 1; $x < $positionGrade; $x++) {
            //finds the group name by iterating over all categories
            if ($row[array_keys($row)[$x]] != null) {
                $groupName = $row[array_keys($row)[$x]];
            }
        }
        $userId = User::select('id')->where('org_defined_id', '=', trim($row['orgdefinedid'], "#"))->first()->id;
        if (!GroupUser::where('user_id', '=', $userId)
            ->where(
                'group_id',
                '=',
                Group::select('id')->where('group_name', '=', $groupName)
                ->where('course_edition_id', '=', $this->editionId)->first()->id
            )->exists()) {
            return new GroupUser([
            'user_id' => $userId,
            'group_id' => Group::select('id')
                ->where('group_name', '=', $groupName)
                ->where('course_edition_id', '=', $this->editionId)->first()->id,
            ]);
        }
    }
}
