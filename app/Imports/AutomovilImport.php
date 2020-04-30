<?php

namespace App\Imports;

use App\AutomovilModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Log;

class AutomovilImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        // $automovilModel = new AutomovilModel();
        // $existePlacas = $automovilModel->existePlacas($row[3]);

        // if ($existePlacas) {
        //     Log::info('======entro existe placa====');
            // return AutomovilModel::where('placas', '=', $row[3])->update([
            //     'created_at' => date('Y-m-d H:i:s')
            // ]);
        // }
        
        // else {
        //     Log::info('======entro no existe placa====');
        //     return new AutomovilModel([
        //         'conductor' => $row[1],
        //         'imagen' => $row[2],
        //         'placas' => $row[3],
        //         'modelo' => $row[4],
        //         'valor' => $row[5],
        //         'observacion' => $row[6],
        //     ]);
        // }

        // return new AutomovilModel([
        //     'conductor' => $row[1],
        //     'imagen' => $row[2],
        //     'placas' => $row[3],
        //     'modelo' => $row[4],
        //     'valor' => $row[5],
        //     'observacion' => $row[6],
        // ]);
    }
}
