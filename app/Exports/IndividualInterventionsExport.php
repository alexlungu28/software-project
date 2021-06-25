<?php

namespace App\Exports;

use App\Models\CourseEdition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class IndividualInterventionsExport implements FromCollection, WithHeadings, WithStrictNullComparison
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
            //from groups table
            'Group',
            //from notes_group table
            'Reason',
            'Action',
            'StartDate',
            'EndDate',
            'Status',
            'StatusNote',
            'TAVisibility'
        ];
    }

    /**
     * Returns a CSV with all individual interventions.
     *
     */
    public function collection()
    {
        return CourseEdition::find($this->editionId)->students()
            ->join(
                'interventions_individual',
                'users.id',
                '=',
                'interventions_individual.user_id'
            )
            ->join(
                'groups',
                'interventions_individual.group_id',
                '=',
                'groups.id'
            )
            ->select(
                'org_defined_id',
                'net_id',
                'last_name',
                'first_name',
                'email',
                'group_name',
                'reason',
                'action',
                'start_day',
                'end_day',
                'status',
                'status_note',
                'visible_ta'
            )->get();
    }
}
