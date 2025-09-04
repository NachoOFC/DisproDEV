<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCierresToRechazosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('rechazos', function (Blueprint $table) {
            $table->softDeletes();
            $table->boolean("estado_pago")->default(false);
            $table->boolean("cierre")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rechazos', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn("estado_pago");
            $table->dropColumn("cierre");
        });
    }
}
