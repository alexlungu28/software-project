<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
     * Returns a CSV with all the entries in the user table.
     *
     * @return Collection
     */
    public function collection(): Collection
    {
        return User::all();
    }
}
