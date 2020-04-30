<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AutomovilModel;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Facades\Storage;

class AutomovilController extends Controller
{
    const VALOR_INGRESO = 200000;

    public function store(Request $request)
    {
        $this->data = $request->except(['_token']);
        $this->data['imagen']= $request->file('imagen');      

       
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

        $path= Storage::disk('public')->put('imagenes/'.$this->data['placas'],$request->file('imagen'));
        $this->data['imagen']= $path;       

        $this->data['valor']=$this->validarValorIngreso($this->data['modelo']);
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
}
