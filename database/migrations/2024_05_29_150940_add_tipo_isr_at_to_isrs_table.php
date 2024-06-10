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
        Schema::table('isrs', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_isr_id')->nullable()->after('porcentaje_excedente');
            $table->foreign('tipo_isr_id')->references('id')->on('tipo_isrs')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isrs', function (Blueprint $table) {
            $table->dropColumn('tipo_isr_id');
        });
    }
};
