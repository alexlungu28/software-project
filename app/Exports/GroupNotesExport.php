<?php

namespace App\Exports;

use App\Models\Rubric;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class GroupNotesExport implements FromCollection, WithHeadings, WithStrictNullComparison
{

    private $editionId;

    /**
     * GroupNotesExport constructor.
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
            //from rubrics table
            'RubricName',
            'Week',
            //from rubric_entries table
            'Description',
            //from rubric_data table
            'Value',
            'Note',
        ];
    }

    /**
     * Returns a CSV with all rubrics.
     *
     */
    public function collection()
    {
        $collection = new Collection();
        $allRubrics = Rubric::all()->where('course_edition_id', '=', $this->editionId);
        foreach ($allRubrics as $rubric) {
            $rubric = $rubric
                ->join(
                    'course_editions',
                    'rubrics.course_edition_id',
                    '=',
                    'course_editions.id'
                )
                ->join(
                    'rubric_entries',
                    'rubrics.id',
                    '=',
                    'rubric_entries.rubric_id'
                )
                ->join(
                    'rubric_data',
                    'rubrics.id',
                    '=',
                    'rubric_data.rubric_id'
                )
                ->join(
                    'groups',
                    'rubric_data.group_id',
                    '=',
                    'groups.id'
                )
                ->whereRaw('rubric_entries.distance = rubric_data.row_number')
                ->where('rubric_entries.is_row', '=', '1')
                ->select(
                    'course_editions.year',
                    'groups.group_name',
                    'rubrics.name',
                    'rubrics.week',
                    'rubric_entries.description',
                    'rubric_data.value',
                    'rubric_data.note'
                )
                ->get();

            $collection = $collection->concat($rubric);
        }
        return $collection;
    }
}
