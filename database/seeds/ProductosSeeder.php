<?php

use App\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = array_map('str_getcsv', file(database_path("imports/productos.csv")));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        foreach ($csv as $row) {
            $row["desde"] = date_create_from_format("j-n-Y" ,$row["desde"]);
            $row["hasta"] = date_create_from_format("j-n-Y", $row["hasta"]);
            Producto::create($row);
        }
    }
}
