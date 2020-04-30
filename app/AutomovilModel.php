<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AutomovilModel extends Model
{
    protected $table = "automovil";

    protected $fillable = [
        'id',
        'conductor',
        'imagen',
        'placas',
        'modelo',
        'valor',
        'observacion',
        'created_at',
        'updated_at'
    ];

    public $timestamps = true;

    public function existePlacas($placas){
        $conteo=$this->where('placas','=',$placas)->count();
        if($conteo>0){
            return true;
        }else{
            return false;
        }
    }

    public function listaAutomovilesXinactivar($arrayNotIn){
        return $this->whereNotIn('placas',$arrayNotIn)->get();
    }


}
