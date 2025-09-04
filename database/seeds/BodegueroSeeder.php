<?php

use App\Bodeguero;
use Illuminate\Database\Seeder;

class BodegueroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $csv = array_map('str_getcsv', file(database_path("imports/bodegueros.csv")));
        array_walk($csv, function (&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });
        array_shift($csv);

        foreach ($csv as $row) {
            Bodeguero::create($row);
        }
    }
}
