<?php

use App\Estado;
use Illuminate\Database\Seeder;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $estados = [
            "ESPERANDO VALIDACION",
            "VALIDADO",
            "EN PROCESAMIENTO",
            "EN BODEGA",
            "LISTO PARA DESPACHO",
            "ENTREGADO",
            "RECIBIDO",
            "RECIBIDO CON OBSERVACIONES",
            "RECHAZADO",
        ];

        foreach($estados as $estado) {
            Estado::create(["nombre" => $estado]);
        }
    }
}
