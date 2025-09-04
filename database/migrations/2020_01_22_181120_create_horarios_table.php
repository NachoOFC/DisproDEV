<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHorariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('empresa_id');
            $table->string('fecha_creacion_inicio');
            $table->string('hora_creacion_inicio');
            $table->string('fecha_creacion_fin');
            $table->string('hora_creacion_fin');
            $table->string('fecha_validacion_inicio');
            $table->string('fecha_validacion_fin');
            $table->string('hora_validacion_inicio');
            $table->string('hora_validacion_fin');
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
        Schema::dropIfExists('horarios');
    }
}
