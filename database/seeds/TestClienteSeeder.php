<?php

use App\Empresa;
use Illuminate\Database\Seeder;

class TestClienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $empresa = Empresa::create([
            "razon_social" => "Empresa Prueba Mline",
            "giro" => "Realizar Pruebas",
            "direccion" => "Puerto Montt",
            "rut" => "82.247.400-7",
        ]);

        $empresa->users()->create([
            "name" => "Supervisor",
            "email" => "supervisor@mline.cl",
            "password" => bcrypt("mlinecl2020")
        ]);

        $centro = $empresa->centros()->create([
            "nombre" => "Centro Mline",
            "direccion" => "Pichi Pelluco",
            "comuna" => "Puerto Montt",
            "ciudad" => "Puerto Montt",
            "zona" => "Puerto Montt"
        ]);

        $centro->users()->create([
            "name" => "Centro",
            "email" => "centro@mline.cl",
            "password" => bcrypt("mlinecl2020")
        ]);
    }
}
