<?php

namespace App\Exports;

use App\AutomovilModel;
use Maatwebsite\Excel\Concerns\FromCollection;

class AutomovilExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return AutomovilModel::all();
    }
}
