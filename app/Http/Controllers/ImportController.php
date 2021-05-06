<?php

namespace App\Http\Controllers;

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
     * @return Application|Factory|View
     */
    public function importExportView()
    {
        return view('import');
    }

    /**
     * @return BinaryFileResponse
     */
    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    /**
     * @return RedirectResponse
     */
    public function import()
    {
        Excel::import(new UsersImport, request()->file('file'));
        Excel::import(new GroupsImport, request()->file('file'));
        Excel::import(new GroupUserImport, request()->file('file'));
        return back();
    }
}
