<?php

namespace App\Exports;

use App\Models\CourseEdition;
use App\Models\Group;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IndividualNotesExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $editionId;

    /**
     * IndividualNotesExport constructor.
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
            'Last Name',
            'First Name',
            'Email',
            //from notes_group table
            'Week',
            'ProblemSignal',
            'Note'
        ];
    }

    /**
     * Returns a CSV with all group notes.
     *
     */
    public function collection()
    {
        return CourseEdition::find($this->editionId)->students()
            ->join(
                'notes_individual',
                'users.id',
                '=',
                'notes_individual.user_id'
            )
            ->select(
                'org_defined_id',
                'net_id',
                'last_name',
                'first_name',
                'email',
                'week',
                'problem_signal',
                'note',
            )->get();
    }
}
