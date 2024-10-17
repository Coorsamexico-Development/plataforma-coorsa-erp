<?php

namespace Database\Seeders;

use App\Models\CamposShe;
use App\Models\DataShe;
use App\Models\SitiosShe;
use App\Models\TablasCamposShe;
use App\Models\TablasShe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SheSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tablas = [
            'Sitio',
            'Analistas',
            'Seafty'
        ];

        $sitios = [
            'GDL',
            'CDU',
            'Procter',
            'Quantum',
            'Scania',
            'Corporativo'
        ];

        $campos = [
            'Ecologia',
            'Salud e Higiene',
            'Seguridad',
            'Documental',
            'Karla Guadalupe',
            'Genda Narashy',
            'Alondra Atzhiri',
            'Zoe Michelle',
            'Fernando Uriel',
            'Jaqueline',
            'Mariana Ixchel'
        ];

        foreach ($tablas as $tabla)
            TablasShe::insert([
                'name' => $tabla,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        foreach ($sitios as $sitio)
            SitiosShe::insert([
                'name' => $sitio,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        foreach ($campos as $campo)
            CamposShe::insert([
                'name' => $campo,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

        $tablasCampo = [
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 1,
                'sitio_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 2,
                'sitio_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 3,
                'sitio_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 1,
                'campo_id' => 4,
                'sitio_id' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 7,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 8,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 9,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 10,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'tabla_id' => 2,
                'campo_id' => 11,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($tablasCampo as $tc)
            TablasCamposShe::insert($tc);
    }
}