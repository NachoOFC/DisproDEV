<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiaDespachosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_despachos', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->foreignId("requerimiento_id")->constrained();

            $table->string("folio");
            $table->string("fecha");
            $table->string("rut_receptor");
            $table->string("razon_social_receptor");
            $table->string("giro_receptor");
            $table->string("direccion_receptor");
            $table->string("comuna_receptor");
            $table->string("nombre_receptor");
            $table->string("ciudad_receptor");
            $table->string("nombre_centro");
            $table->string("direccion_destino");
            $table->string("comuna_destino");
            $table->string("ciudad_destino");
            $table->string("transporte_rut");
            $table->string("transporte_nombre");
            $table->string("febos_id")->nullable();

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
        Schema::dropIfExists('guia_despachos');
    }
}
