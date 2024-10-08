<?php

namespace Database\Seeders;

use App\Models\UserRedes;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRedesSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRedes::insert([
            'user_id' => 1,
            'redes_id' => 2,
            'value' => 'https://www.linkedin.com/in/renato-ortiz-coorsa/',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRedes::insert([
            'user_id' => 1075,
            'redes_id' => 2,
            'value' => 'https://www.linkedin.com/in/vania-jimenez-coorsa/',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRedes::insert([
            'user_id' => 1,
            'redes_id' => 5,
            'value' => 'https://storage.googleapis.com/coorsamexico_erp/vCards/Renato.vcf',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRedes::insert([
            'user_id' => 1075,
            'redes_id' => 5,
            'value' => 'https://storage.googleapis.com/coorsamexico_erp/vCards/Vania.vcf',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
