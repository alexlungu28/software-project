<?php

namespace App\Http\Controllers;

use App\Exports\GradesExport;
use App\Exports\UsersExport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * View of the export page.
     *
     * @return Application|Factory|View
     */
    public function exportView($editionId)
    {
        return view('export', [
            "edition_id" => $editionId,
        ]);
    }


    /**
     * Exports the user list.
     *
     * @param $editionId
     * @return BinaryFileResponse
     */
    public function exportUserList($editionId): BinaryFileResponse
    {
        return Excel::download(new UsersExport($editionId), 'user_list.csv');
    }

    /**
     * Exports grades.
     *
     * @param $editionId
     * @return BinaryFileResponse
     */
    public function exportIndividualGrades($editionId): BinaryFileResponse
    {
        return Excel::download(new GradesExport($editionId), 'grades.csv');
    }
}
