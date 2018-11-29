<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'name' => $faker->unique()->word . "." . $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'description' => $faker->sentence(10,true),
        'file_ext' => $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'size' => $faker->randomDigit . ".MB",
        'url' => $faker->url,
        'size_int' => $faker->randomDigit,
        'slug' => $faker->unique()->slug,
        'status' =>'New',
        'owner_id'=> App\User::all()->random()->id,
        'document_type_id'=> App\DocumentType::all()->random()->id,
        'unit_id' => App\Units::all()->random()->id
    ];
});
