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
        Schema::create('data_shes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabla_campo_id')->constrained('tablas_campos_shes');
            $table->integer('value');
            $table->date('año_mes');
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
        Schema::dropIfExists('data_shes');
    }
};