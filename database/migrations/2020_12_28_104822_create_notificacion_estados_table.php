<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificacionEstadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notificacion_estados', function (Blueprint $table) {
            $table->id();
            $table->foreignId("estado_id")->constrained();
            $table->boolean("centro")->default(false);
            $table->boolean("supervisor")->default(false);
            $table->boolean("logistica")->default(false);
            $table->boolean("compras")->default(false);
            $table->boolean("despacho")->default(false);
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
        Schema::dropIfExists('notificacion_estados');
    }
}
