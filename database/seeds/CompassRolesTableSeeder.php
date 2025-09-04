<?php

use App\CompassRole;
use App\User;
use Illuminate\Database\Seeder;

class CompassRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new CompassRole;
        $role->name = 'Compras';
        $role->save();

        $role->users()->createMany([
            [
                "name" => "Compras",
                "email" => "compras@mline.cl",
                "password" => bcrypt("mlinecl2020")
            ],
            [
                "name" => "Rodolfo Zambelich",
                "email" => "rodolfo.zambelich@compass-group.cl",
                "password" => bcrypt("compass2020")
            ],
            [
                "name" => "Sofia Valdes",
                "email" => "sofia.valdes@compass-group.cl",
                "password" => bcrypt("compass2020")
            ],
        ]);

        $role = new CompassRole;
        $role->name = 'Despacho';
        $role->save();

        $role->users()->createMany([
            [
                "name" => "Despacho",
                "email" => "despacho@mline.cl",
                "password" => bcrypt("mlinecl2020")
            ],
            [
                "name" => "Carlos Melipillan",
                "email" => "carlos.melipillan@compass-group.cl",
                "password" => bcrypt("compass2020")
            ],
        ]);
    }
}
