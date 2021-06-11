<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersExport implements FromQuery, WithHeadings
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
            'org_defined_id',
            'net_id',
            'last_name',
            'first_name',
            'email',
            'affiliation',
            //from course_edition_user table
            'role',
        ];
    }

    /**
     * Returns a CSV with all the entries in the user list.
     *
     */
    public function query()
    {
        return User::query()
            ->join('course_edition_user', 'users.id', '=', 'user_id')
            ->where('course_edition_id', '=', $this->editionId)
            ->select(
                'org_defined_id',
                'net_id',
                'last_name',
                'first_name',
                'email',
                'affiliation',
                'role'
            );
    }
}
