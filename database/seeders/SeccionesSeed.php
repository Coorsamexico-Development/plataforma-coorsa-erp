<?php

namespace Database\Seeders;

use App\Models\CiSeccion;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeccionesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CiSeccion::insert([
            'name' => 'SUA',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CiSeccion::insert([
            'name' => 'Nomina',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CiSeccion::insert([
            'name' => 'CXP',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CiSeccion::insert([
            'name' => 'Atas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CiSeccion::insert([
            'name' => 'Bajas',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        CiSeccion::insert([
            'name' => 'Maniobras',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}