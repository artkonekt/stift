<?php

use Faker\Generator as Faker;
use \Konekt\AppShell\Models\User;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueStatus;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\Severity;

$factory->define(Issue::class, function (Faker $faker) {
    return [
        'project_id'    => function () {
            return factory(Project::class)->create()->id;
        },
        'issue_type_id' => function () {
            return factory(IssueType::class)->create()->id;
        },
        'severity_id'   => function () {
            return factory(Severity::class)->create()->id;
        },
        'subject'       => $faker->words(mt_rand(1, 7)),
        'status'        => IssueStatus::defaultValue(),
        'priority'      => $faker->randomNumber(2),
        'created_by'    => function () {
            return factory(User::class)->create()->id;
        },
    ];
});
