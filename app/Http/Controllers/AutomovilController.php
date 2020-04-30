<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutomovilModel;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AutomovilExport;
use App\Imports\AutomovilImport;

class AutomovilController extends Controller
{
    const VALOR_INGRESO = 200000;

    public function store(Request $request)
    {
        $this->data = $request->except(['_token']);
        $this->data['imagen'] = $request->file('imagen');


        $rules = [
            'conductor'              => 'required',
            'placas'                 => 'required|unique:automovil,placas',
            'modelo'                 => 'required|date_format:"Y"',
            'imagen'                 => 'required|image'

        ];

        $validator = \Validator::make($this->data, $rules);

        if ($validator->fails()) {
            $errors = [];
            foreach ($this->data as $key => $value) {
                if (!empty($validator->errors()->first($key))) {
                    array_push($errors, array($key => $validator->errors()->first($key)));
                }
            }

            return response()->json(array(
                'created' => false,
                'errors'  => $errors
            ), 200);
        }

        $path = Storage::disk('public')->put('imagenes/' . $this->data['placas'], $request->file('imagen'));
        $this->data['imagen'] = $path;

        $this->data['valor'] = $this->validarValorIngreso($this->data['modelo']);
        AutomovilModel::create($this->data);

        return response()->json(array(
            'created' => true,
            'message-respuesta'  => 'Automóvil guardado correctamente'
        ), 200);
    }

    public function validarValorIngreso($modelo)
    {

        // $date = Carbon::createFromFormat('Y-m-d', '2020-04-29');
        $date = Carbon::now();

        if ($date->day % 2 == 0) {

            $valorPorcentaje5 = (self::VALOR_INGRESO * 0.05);
            $valor = self::VALOR_INGRESO + $valorPorcentaje5;
        } else {
            $valor = self::VALOR_INGRESO;
        }

        if ($modelo <= 1997) {
            $valorPorcentaje20 = ($valor * 0.20);
            $valor = $valor + $valorPorcentaje20;
        }

        return $valor;
    }


    public function exportarExcel()
    {
        return Excel::download(new AutomovilExport, "automoviles.xlsx");
    }
    public function importarExcel(Request $request)
    {
        $archivo = $request->file('archivo_excel');

        // dd(Excel::load($archivo, function($reader){})->get());
        // Excel::selectSheetsByIndex(0)->load($request->excel, function($reader) {

        //     //$reader->formatDates(true, 'd-m-Y');

        //     $excel = $reader->get();

        //     $this->errors = [];
        //     $this->rowNumber = 0;

        //     $excel->each(function($row) {

        //         Log::info('======recorriendo el excel======');
        //     });
        // });
        // Excel::import(new AutomovilImport,$archivo);

        $data = Excel::toArray(new AutomovilImport, $archivo);

        // Log::info($data);

        collect(head($data))
            ->each(function ($row, $key) {
                $automovilModel = new AutomovilModel();
                $existePlacas = $automovilModel->existePlacas($row[3]);

                if ($existePlacas) {
                    Log::info('======entro existe placa====');
                    return AutomovilModel::where('placas', '=', $row[3])->update([
                        'created_at' => date('Y-m-d H:i:s')
                    ]);
                }else {
                        Log::info('======entro no existe placa====');

                        AutomovilModel::create([
                            'conductor' => $row[1],
                            'imagen' => $row[2],
                            'placas' => $row[3],
                            'modelo' => $row[4],
                            'valor' => $row[5],
                            'observacion' => $row[6],
                        ]);
                        
                    }
                // Log::info($row[2]);
                // DB::table('produk')
                //     ->where('id_produk', $row['id'])
                //     ->update(array_except($row, ['id']));
            });
    }
}
