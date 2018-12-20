<?php

use Faker\Generator as Faker;

$factory->define(App\DocumentNum::class, function (Faker $faker) {
    return [
        'number' => $faker->numberBetween($min = 1000, $max = 9000) . "/" . $faker->randomElement(['SOW','Manual']) . "/SD6/2018",
        'name' => $faker->unique()->word,
        'used' =>  $faker->randomElement([1,0]),
        'document_type_id' => App\DocumentType::all()->random()->id
    ];
});
