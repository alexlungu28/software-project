<?php

namespace App\Exports;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GradesExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $editionId;

    /**
     * GradesExport constructor.
     * @param int $editionId
     */
    public function __construct(int $editionId)
    {
        $this->editionId = $editionId;
    }

    /**
     * Heading row containing information about each column.
     *
     * @return string[]
     *
     */
    public function headings(): array
    {
        return [
            //from user table
            'OrgDefinedId',
            'Username',
            //from groups table
            'GroupGrade',
            'IndividualGrade',
        ];
    }

    /**
     * Returns a CSV with all grades.
     *
     */
    public function collection()
    {
        $collection = new Collection();
        $allGroups = Group::all()->where('course_edition_id', '=', $this->editionId);
        foreach ($allGroups as $group) {
            $groupGrades = $group->usersWithGrade()
                ->join('users', 'group_user.user_id', '=', 'users.id')
                ->join(
                    'course_edition_user',
                    'group_user.user_id',
                    '=',
                    'course_edition_user.user_id'
                )
                ->where('role', '=', 'student')
                ->whereRaw('course_edition_user.course_edition_id = groups.course_edition_id')
                ->select(
                    'org_defined_id',
                    'net_id',
                    'grade',
                    'student_grade',
                )
                ->get();

            $collection = $collection->concat($groupGrades);
        }
        return $collection;
    }
}
