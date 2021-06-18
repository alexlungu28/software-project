<?php

namespace App\Exports;

use App\Models\Group;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RubricsExport implements FromCollection, WithHeadings
{

    private $editionId;

    /**
     * RubricsExport constructor.
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
            //from rubrics table
            'RubricName',
            'Week',
            //from course_editions table
            'CourseEdition',
            //from rubric_entries table
            'Description',
            //from rubric_data table
            'Value',
            'Note',
        ];
    }

    /**
     * Returns a CSV with all individual grades.
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
