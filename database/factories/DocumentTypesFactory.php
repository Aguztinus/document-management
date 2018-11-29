<?php

use Faker\Generator as Faker;

$factory->define(App\DocumentType::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word,
        'description' => $faker->sentence(10,true)
    ];
});
