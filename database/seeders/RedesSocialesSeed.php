<?php

namespace Database\Seeders;

use App\Models\RedesSociales;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RedesSocialesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $redes = ['Facebook', 'LinkedIn', 'X', 'Web', 'vCard'];

        foreach ($redes as $red)
            RedesSociales::insert([
                'name' => $red,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
    }
}
