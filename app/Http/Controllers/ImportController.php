<?php

namespace App\Http\Controllers;

use App\Imports\CourseEditionTAImport;
use App\Imports\CourseEditionStudentImport;
use App\Imports\GroupsImport;
use App\Imports\GroupsTAImport;
use App\Imports\GroupUserImport;
use App\Imports\GroupUserTAImport;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\Imports\UsersImport;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

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
        Excel::import(new CourseEditionStudentImport($editionId), request()->file('file'));
        return back();
    }

    /**
     * Imports TAs inside the users table,
     * the role inside the course_edition_user table.
     *
     * @param $editionId - the course edition where the TAs will be imported
     * @return RedirectResponse - returns the user back to the import page
     */
    public function importTA($editionId): RedirectResponse
    {
        Excel::import(new UsersImport, request()->file('file'));
        Excel::import(new GroupsTAImport($editionId), request()->file('file'));
        Excel::import(new GroupUserTAImport($editionId), request()->file('file'));
        Excel::import(new CourseEditionTAImport($editionId), request()->file('file'));
        return back();
    }
}
