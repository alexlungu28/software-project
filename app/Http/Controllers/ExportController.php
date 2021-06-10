<?php

namespace App\Http\Controllers;

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
     * Exports users table.
     *
     * @return BinaryFileResponse
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
