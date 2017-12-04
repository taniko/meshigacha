<?php

use Faker\Generator as Faker;
use App\Category;

$factory->define(Category::class, function (Faker $faker) {
    do {
        $name = $faker->words(1, true);
    } while (Category::where('name', $name)->exists());
    
    return [
        'name' => $name,
    ];
});
