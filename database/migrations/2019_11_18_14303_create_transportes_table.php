<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transportes', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('abastecimiento_id')->constrained();
            $table->string('nombre_chofer');
            $table->string('rut_chofer');
            $table->string('patente');
            $table->string("rut_empresa");
            $table->string('contacto');
            $table->date('fecha_programada');
            $table->boolean('despachado')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('transportes');
    }
}
