<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'number'=> $faker->numberBetween($min = 1000, $max = 9000) . "/" . $faker->randomElement(['SOW','Manual']) . "/SD6/2018",
        'name' => $faker->unique()->word . "." . $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'realname' =>  App\DocumentNum::all()->random()->name,
        'description' => $faker->sentence(10,true),
        'file_ext' => $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'size' => $faker->randomDigit . ".MB",
        'url' => $faker->url,
        'size_int' => $faker->randomDigit,
        'slug' => $faker->unique()->slug,
        'status' =>'New',
        'public' => 1,
        'owner_id'=> App\User::all()->random()->id,
        'author_id'=> App\DocumentAutor::all()->random()->id,
        'document_type_id'=> App\DocumentType::all()->random()->id,
        'document_num_id' => App\DocumentNum::all()->random()->id,
        'unit_id' => App\Units::all()->random()->id
    ];
});
