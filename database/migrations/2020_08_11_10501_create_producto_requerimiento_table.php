<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoRequerimientoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('producto_requerimiento', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('producto_id')->constrained();
            $table->foreignId('requerimiento_id')->constrained();
            $table->unsignedDecimal('cantidad')->nullable();
            $table->unsignedDecimal('real')->nullable();
            $table->unsignedInteger("precio")->nullable();
            $table->string('observacion')->nullable();
            $table->date("fecha_vencimiento")->nullable();
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
        Schema::dropIfExists('producto_requerimiento');
    }
}
