<?php

use Faker\Generator as Faker;
use Konekt\Stift\Models\IssueType;

$factory->define(IssueType::class, function (Faker $faker) {
    $name = ucwords($faker->unique()->word);

    return [
        'id'   => strtolower($name),
        'name' => $name
    ];
});

$factory->state(IssueType::class, 'bug', [
    'id'   => 'bug',
    'name' => 'Bug'
]);

$factory->state(IssueType::class, 'ticket', [
    'id'   => 'ticket',
    'name' => 'Task'
]);

$factory->state(IssueType::class, 'task', [
    'id'   => 'task',
    'name' => 'Ticket'
]);
