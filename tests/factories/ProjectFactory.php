<?php

use Faker\Generator as Faker;
use Konekt\Customer\Models\Customer;
use Konekt\Stift\Models\Project;

$factory->define(Project::class, function (Faker $faker) {
    return [
        'name'        => $faker->words(rand(1, 3), true),
        'customer_id' => function () {
            return factory(Customer::class)->create()->id;
        },
    ];
});
