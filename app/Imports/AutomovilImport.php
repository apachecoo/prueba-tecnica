<?php

namespace App\Imports;

use App\AutomovilModel;
use Maatwebsite\Excel\Concerns\ToModel;

class AutomovilImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new AutomovilModel([
            'conductor' => $row[1],
            'imagen' => $row[2],
            'placas' => $row[3],
            'modelo' => $row[4],
            'valor' => $row[5],
            'observacion' => $row[6],
        ]);
    }
}
