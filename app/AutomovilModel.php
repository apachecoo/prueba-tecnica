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
}
