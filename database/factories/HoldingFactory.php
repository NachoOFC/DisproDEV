<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Holding;
use Faker\Generator as Faker;

$factory->define(Holding::class, function (Faker $faker) {
  return [
    'nombre' => $faker->domainName
  ];
});
