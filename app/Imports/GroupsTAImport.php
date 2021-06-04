<?php

namespace App\Imports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class GroupsTAImport implements ToModel, WithHeadingRow
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
     * Adds a group in the database.
     *
     * @param array $row
     *
     * @return Group
     */
    public function model(array $row)
    {
        $groups = $row[array_keys($row)[5]];
        foreach (preg_split("/; /", $groups) as $groupName) {
            if ($groupName != null && !Group::where('group_name', '=', $groupName)
                    ->where('course_edition_id', '=', $this->editionId)->exists()) {
                Group::create([
                    'group_name'    => $groupName,
                    'course_edition_id' => $this->editionId,
                ]);
            } else {
                return null;
            }
        }
        return null;
    }
}
