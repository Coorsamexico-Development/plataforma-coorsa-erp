<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isrs', function (Blueprint $table) {
            $table->id();
            $table->float('limite_inferior');
            $table->float('limite_superior')->nullable();
            $table->float('cuota_fija');
            $table->float('porcentaje_excedente');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('isrs');
    }
};
