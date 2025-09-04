<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run()
  {
      $this->call([
          EmpresaSeeder::class,
          CentroSeeder::class,
          AbastecimientoSeeder::class,
          CompassRolesTableSeeder::class,
      ]);
  }
}
