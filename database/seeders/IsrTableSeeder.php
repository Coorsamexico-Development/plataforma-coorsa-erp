<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IsrTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ISR::create([
            'limite_inferior' => 0.01 ,
            'limite_superior' => 746.04 ,
            'cuota_fija' => 0.00,
            'porcentaje_excedente' => 1.92 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 746.05 ,
            'limite_superior' => 6332.05 ,
            'cuota_fija' => 14.32,
            'porcentaje_excedente' => 6.40 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 6332.06 ,
            'limite_superior' => 11128.01 ,
            'cuota_fija' => 371.83,
            'porcentaje_excedente' => 10.88 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 11128.02 ,
            'limite_superior' => 12935.82 ,
            'cuota_fija' => 893.63,
            'porcentaje_excedente' => 16.00 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 12935.83 ,
            'limite_superior' => 15487.71 ,
            'cuota_fija' => 1182.88,
            'porcentaje_excedente' => 17.92 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 15487.72 ,
            'limite_superior' => 31236.49 ,
            'cuota_fija' => 1640.18,
            'porcentaje_excedente' => 21.36 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 31236.50 ,
            'limite_superior' => 49233.00 ,
            'cuota_fija' => 5004.12,
            'porcentaje_excedente' => 23.52 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 49233.01 ,
            'limite_superior' => 93993.90 ,
            'cuota_fija' => 9236.89,
            'porcentaje_excedente' => 30.00 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 93993.91 ,
            'limite_superior' => 125325.20 ,
            'cuota_fija' => 22665.17,
            'porcentaje_excedente' => 32.00 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 125325.21 ,
            'limite_superior' => 375975.61 ,
            'cuota_fija' => 32691.18,
            'porcentaje_excedente' => 34.00 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

        ISR::create([
            'limite_inferior' => 375975.62 ,
            'limite_superior' => null ,
            'cuota_fija' => 117912.32,
            'porcentaje_excedente' => 35.00 ,
            'activos' => 1 ,
            'tipo_isr_id' => 1,
        ]);

    }
}
