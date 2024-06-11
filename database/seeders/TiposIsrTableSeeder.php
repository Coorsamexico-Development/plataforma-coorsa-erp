<?php

namespace Database\Seeders;

use App\Models\TipoIsr;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TiposIsrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoIsr::create([
            'tipo_isr' => 'mensual',
        ]);
    }
}
