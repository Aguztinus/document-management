<?php

use Faker\Generator as Faker;

$factory->define(App\Units::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'description' => $faker->sentence(10,true)
    ];
});
