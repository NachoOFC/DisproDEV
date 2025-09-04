<?php

use App\Abastecimiento;
use Illuminate\Database\Seeder;

class AbastecimientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Abastecimiento::create([
            "nombre" => 'Muelle Frowuar',
            "comuna" => 'Calbuco',
            "ciudad" => 'San Jose'
        ]);

        Abastecimiento::create([
            "nombre" => 'Muelle Oxxean',
            "comuna" => 'Puerto Montt',
            "ciudad" => 'Chinquihue'
        ]);

        Abastecimiento::create([
            "nombre" => 'Puerto Cisnes',
            "comuna" => 'Cisnes',
            "ciudad" => 'Puerto Cisnes'
        ]);

        Abastecimiento::create([
            "nombre" => 'Melinka',
            "comuna" => 'Guaitecas',
            "ciudad" => 'Melinka'
        ]);
    }
}
