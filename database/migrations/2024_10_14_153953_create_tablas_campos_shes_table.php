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
        Schema::create('tablas_campos_shes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tabla_id')->constrained('tablas_shes');
            $table->foreignId('campo_id')->constrained('campos_shes');
            $table->foreignId('sitio_id')->nullable()->constrained('sitios_shes');
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
        Schema::dropIfExists('tablas_campos_shes');
    }
};