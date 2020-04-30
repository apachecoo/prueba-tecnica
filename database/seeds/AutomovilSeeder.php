<?php

use Illuminate\Database\Seeder;
use App\AutomovilModel;

class AutomovilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AutomovilModel::create([
            'id'=>1,
            'conductor' => 'Conductor 1',
            'placas' => 'KCI-232',
            'modelo' => '1997',
            'valor' => 252000,
            'observacion'=> null
        ]);

        AutomovilModel::create([
            'id'=>2,
            'conductor' => 'Conductor 2',
            'placas' => 'KTO-233',
            'modelo' => '1970',
            'valor' => 252000,
            'observacion'=> null
        ]);

        AutomovilModel::create([
            'id'=>3,
            'conductor' => 'Conductor 3',
            'placas' => 'KCI-244',
            'modelo' => '1996',
            'valor' => 252000,
            'observacion'=> null
        ]);
    }
}
