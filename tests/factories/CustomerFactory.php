<?php

use Faker\Generator as Faker;
use Konekt\Customer\Models\Customer;

$factory->define(Customer::class, function (Faker $faker) {
    return [
        'company_name' => $faker->company
    ];
});
