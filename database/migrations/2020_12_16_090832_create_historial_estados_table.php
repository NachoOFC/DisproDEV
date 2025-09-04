<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistorialEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId("requerimiento_id")->constrained();
            $table->foreignId("estado_id")->constrained();
            $table->foreignId("user_id")->constrained();
            $table->string("observacion")->nullable();
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
        Schema::dropIfExists('historial_estados');
    }
}
