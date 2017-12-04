<?php

use Faker\Generator as Faker;

$factory->define(App\Food::class, function (Faker $faker) {
    return [
        'name'      => $faker->words(1, true),
        'calorie'   => rand(100, 1000),
        'red'       => $faker->randomFloat(1, 0.1, 10),
        'green'     => $faker->randomFloat(1, 0.1, 10),
        'yellow'    => $faker->randomFloat(1, 0.1, 10),
        'price'     => rand(100, 2000),
    ];
});
