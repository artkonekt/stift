<?php

use Faker\Generator as Faker;
use Konekt\Stift\Models\Severity;

$factory->define(Severity::class, function (Faker $faker) {
    $name = ucwords($faker->unique()->word);

    return [
        'id'     => strtolower($name),
        'name'   => $name,
        'weight' => $faker->randomDigitNotNull
    ];
});

$factory->state(Severity::class, 'low', [
    'id'     => 'low',
    'name'   => 'Low',
    'weight' => 1
]);

$factory->state(Severity::class, 'medium', [
    'id'     => 'medium',
    'name'   => 'Medium',
    'weight' => 5
]);

$factory->state(Severity::class, 'high', [
    'id'     => 'high',
    'name'   => 'High',
    'weight' => 8
]);

$factory->state(Severity::class, 'critical', [
    'id'     => 'critical',
    'name'   => 'Critical',
    'weight' => 10
]);
