<?php

namespace App\Exports;

use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GroupInterventionsExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $editionId;

    /**
     * GroupInterventionsExport constructor.
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
            //from groups table
            'Group',
            //from notes_group table
            'Week',
            'ProblemSignal',
            'Note'
        ];
    }

    /**
     * Returns a CSV with all group interventions.
     *
     */
    public function collection()
    {
        return Group::where('groups.course_edition_id', '=', $this->editionId)
            ->join(
                'notes_group',
                'groups.id',
                '=',
                'notes_group.group_id'
            )
            ->select(
                'group_name',
                'week',
                'problem_signal',
                'note',
            )->get();
    }
}
