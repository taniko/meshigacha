<?php

use Faker\Generator as Faker;

$factory->define(App\Restaurant::class, function (Faker $faker) {
    return [
        'name'      => $faker->words(2, true),
        'address'   => $faker->address,
        'phone'     => $faker->phoneNumber,
        'email'     => $faker->email,
    ];
});
