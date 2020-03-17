<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Outlet;
use Faker\Generator as Faker;

$factory->define(Outlet::class, function (Faker $faker) {
    return [
        'code' => $faker->uuid,
        'name' => $faker->name(),
        'status' => 1,
        'address' => $faker->address(),
        'phone' => $faker->phoneNumber(),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
