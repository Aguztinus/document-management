<?php

use Faker\Generator as Faker;

$factory->define(App\Document::class, function (Faker $faker) {
    return [
        'number'=> App\DocumentNum::all()->random()->number,
        'name' => $faker->unique()->word . "." . $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'realname' =>  App\DocumentNum::all()->random()->name,
        'description' => $faker->sentence(10,true),
        'file_ext' => $faker->randomElement(['pdf', 'doc', 'txt', 'jpg', 'png', 'xls','docx','xlsx','zip']),
        'size' => $faker->randomDigit . ".MB",
        'url' => $faker->url,
        'size_int' => $faker->randomDigit,
        'slug' => $faker->unique()->slug,
        'status' =>'New',
        'owner_id'=> App\User::all()->random()->id,
        'author_id'=> App\DocumentAutor::all()->random()->id,
        'document_type_id'=> App\DocumentType::all()->random()->id,
        'document_num_id' => App\DocumentNum::all()->random()->id,
        'unit_id' => App\Units::all()->random()->id
    ];
});
