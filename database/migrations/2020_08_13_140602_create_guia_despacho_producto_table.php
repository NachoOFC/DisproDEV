<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuiaDespachoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guia_despacho_producto', function (Blueprint $table) {
            $table->id();

            $table->foreignId("guia_despacho_id")->constrained();
            $table->foreignId("producto_id")->constrained();

            $table->string("cantidad");
            $table->string("precio");
            $table->string("real");
            $table->string("observacion")->nullable();
            $table->string("fecha_vencimiento")->nullable();

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
        Schema::dropIfExists('guia_despacho_producto');
    }
}
