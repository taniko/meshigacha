<?php

use Faker\Generator as Faker;
use App\Foodstuff;

$factory->define(Foodstuff::class, function (Faker $faker) {
    do {
        $name = $faker->words(1, true);
    } while (Foodstuff::where('name', $name)->exists());

    return [
        'name' => $name
    ];
});
