<?php

namespace App\Imports;

use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupUserTAImport implements ToModel, WithHeadingRow
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
        $userId = User::select('id')->where('org_defined_id', '=', trim($row['orgdefinedid'], "#"))->first()->id;
        $groups = $row[array_keys($row)[5]];
        foreach(preg_split("/; /", $groups) as $groupName) {
            if ($groupName != null && !GroupUser::where('user_id', '=', $userId)
                ->where(
                    'group_id',
                    '=',
                    Group::select('id')->where('group_name', '=', $groupName)
                        ->where('course_edition_id', '=', $this->editionId)->first()->id
                )->exists()) {
                GroupUser::create([
                    'user_id' => $userId,
                    'group_id' => Group::select('id')
                        ->where('group_name', '=', $groupName)
                        ->where('course_edition_id', '=', $this->editionId)->first()->id,
                ]);
            }
        }
    }
}
