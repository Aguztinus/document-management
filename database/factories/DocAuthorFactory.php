<?php

use Faker\Generator as Faker;

$factory->define(App\DocumentAutor::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail
    ];
});