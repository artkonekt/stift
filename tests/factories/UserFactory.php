<?php

use Faker\Generator as Faker;
use Konekt\AppShell\Models\User;

$factory->define(User::class, function (Faker $faker) {
    return [
        'email' => $faker->unique()->email
    ];
});
