<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Abastecimiento;
use Faker\Generator as Faker;

$factory->define(Abastecimiento::class, function (Faker $faker) {
  return [
    'nombre' => $faker->domainName
  ];
});
