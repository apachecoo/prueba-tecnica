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
            'modelo'                 => 'required|date_format:"Y"',
            'imagen'                 => 'required|image'
        ];
        if (empty($this->data['hidden_id']) && !is_numeric($this->data['hidden_id'])) {
            $rules['placas'] = 'required|unique:automovil,placas';
        }

        

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
        $this->data['conductor'] = ucwords($this->data['conductor']);
        $this->data['placas'] = strtoupper($this->data['placas']);

        if (!empty($this->data['hidden_id']) && is_numeric($this->data['hidden_id'])) {
            // $datosActualizar= $request->except(['_token','hidden_id']);
            // $datosActualizar['imagen']=$this->data['imagen'];
            $datosActualizar=$this->data;
            unset($datosActualizar['hidden_id']);

            AutomovilModel::where('id', '=', $this->data['hidden_id'])->update($datosActualizar);
        } else {
            AutomovilModel::create($this->data);
        }


        return response()->json(array(
            'created' => true,
            'message-respuesta'  => 'Automóvil guardado correctamente'
        ), 200);
    }

    public function validarValorIngreso($modelo)
    {

         $date = Carbon::createFromFormat('Y-m-d', '2020-05-06');
        // $date = Carbon::now();

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
        $data = Excel::toArray(new AutomovilImport, $archivo);


        $automovilesHabilitados = [];
        foreach ($data as $key => $rowa) {
            foreach ($rowa as $keyb => $rowb) {
                $automovilesHabilitados[] = $rowb[1];
            }
        }


        collect(head($data))
            ->each(function ($row, $key) {

                $automovilModel = new AutomovilModel();
                $existePlacas = $automovilModel->existePlacas(trim($row[1]));

                if ($existePlacas) {
                    AutomovilModel::where('placas', '=', trim($row[1]))->update([
                        'estado' => 1,
                        'created_at' => date('Y-m-d H:i:s')
                    ]);

                } else {
                    AutomovilModel::create([
                        'conductor' => ucwords($row[0]),
                        'placas' => strtoupper(trim($row[1])),
                        'modelo' => $row[2],
                        'valor' => $this->validarValorIngreso($row[2]),
                        'observacion' => $row[4],
                    ]);
                }
            });


       
        $automovilesPorInactivar = $this->getAutomovilModel()->listaAutomovilesXinactivar($automovilesHabilitados);
        foreach ($automovilesPorInactivar as $key => $automovil) {
            $actualizarAutomovil = AutomovilModel::find($automovil->id);
            $actualizarAutomovil->estado = 0;
            $actualizarAutomovil->created_at = date('Y-m-d H:i:s');
            $actualizarAutomovil->save();
        }

        return redirect()->route('home');
    }

    public function destroy($id)
    {

        try {
            AutomovilModel::destroy($id);
            return response()->json(array(
                'deleted' => true,
            ), 200);
        } catch (\Exception $e) {
            Log::critical("Error al eliminar automovil. Detalles del error: {$e->getCode()},{$e->getLine()},{$e->getMessage()}");
            return response()->json(array(
                'deleted' => false,
                'details' => 'Error Inesperado: por favor contactarse con el administrador del sitio',
            ), 200);
        }
    }

    public function getAutomovilModel()
    {
        return new AutomovilModel();
    }

    public function show($id)
    {

        return AutomovilModel::find($id);
    }
}
