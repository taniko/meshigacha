<?php

use Faker\Generator as Faker;
use App\Allergy;

$factory->define(Allergy::class, function (Faker $faker) {
    do {
        $name = $faker->words(1, true);
    } while (Allergy::where('name', $name)->exists());

    return [
        'name' => $name,
    ];
});
