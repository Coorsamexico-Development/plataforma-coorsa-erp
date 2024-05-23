<?php

namespace Database\Seeders;

use App\Models\CiAño;
use App\Models\CiMese;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MesesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];

    public function run()
    {
        foreach ($this->meses as $mes)
            CiMese::insert([
                'mes' => $mes,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        CiAño::insert([
            'año' => 2024,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
