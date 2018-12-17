<?php

use Faker\Generator as Faker;

$factory->define(App\UsersHistory::class, function (Faker $faker) {
    return [
        //
        'user_id' =>App\User::all()->random()->id,
        'user_name' => App\User::all()->random()->name,
        'description' => $faker->sentence(10,true),
        'ip' =>  $faker->ipv4,
        'action' => $faker->randomElement(['save', 'edit', 'delete']),
        'status' => $faker->randomElement([1, 0, 2]),
        'created_at' =>  $faker->dateTime($max = 'now', $timezone = null)
    ];
});
