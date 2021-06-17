<?php

namespace App\Exports;

use App\Models\CourseEdition;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromCollection, WithHeadings
{

    private $editionId;

    /**
     * UsersExport constructor.
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
            'Affiliation',
            //from course_edition_user table
            'Role',
        ];
    }

    /**
     * Returns a CSV with all the entries in the user list.
     *
     */
    public function collection()
    {
        return CourseEdition::find($this->editionId)->usersWithRole()
            ->select(
                'org_defined_id',
                'net_id',
                'last_name',
                'first_name',
                'email',
                'affiliation',
                'role'
            )->get();
    }
}
