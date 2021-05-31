<?php

namespace App\Http\Controllers;

use App\Imports\CourseEditionUserImport;
use App\Imports\GroupsImport;
use App\Imports\GroupUserImport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ImportController extends Controller
{
    /**
     * View of the import export page.
     *
     * @return Application|Factory|View
     */
    public function importExportView($editionId)
    {
        return view('import', [
            "edition_id" => $editionId,
        ]);
    }

    /**
     * Exports users table.
     *
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * Imports users inside the users table,
     * groups inside the groups table,
     * the link between the former two inside the group_user table,
     * the role inside the course_edition_user table.
     *
     * @param $editionId - the course edition where the students will be imported
     * @return RedirectResponse - returns the user back to the import page
     */
    public function import($editionId): RedirectResponse
    {

        Excel::import(new UsersImport, request()->file('file'));
        Excel::import(new GroupsImport($editionId), request()->file('file'));
        Excel::import(new GroupUserImport($editionId), request()->file('file'));
        Excel::import(new CourseEditionUserImport($editionId), request()->file('file'));
        return back();
    }
}
