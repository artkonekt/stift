<?php
/**
 * Contains the ProjectSeveritiesTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Tests\Feature;

use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Contracts\Severity;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\SeverityProxy;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestProjects;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestSeverities;
use Konekt\Stift\Tests\TestCase;

class ProjectSeveritiesTest extends TestCase
{
    use CreatesTestClients, CreatesTestProjects, CreatesTestSeverities;

    public function testSeverityCanBeAssignedToProject()
    {
        $this->createTestProjects();

        $this->project1->severities()->create([
            'id'     => 'galactic',
            'name'   => 'Galaxy Is About To Die',
            'weight' => PHP_INT_MAX // HAHAHA I'm funny
        ]);

        // Refetch from db
        $project1 = ProjectProxy::find($this->project1->id);

        $this->assertCount(1, $project1->severities);
        $this->assertArrayHasKey('galactic',
            $project1->severities->keyBy('id')->all()
        );
    }

    public function testMultipleSeveritiesCanBeAssignedToAProject()
    {
        $this->createTestProjects();

        $this->project1->severities()->create([
            'id'     => 'pointless',
            'name'   => 'Why You\'re Sharing All This Information With Me?',
            'weight' => 0
        ]);

        $this->project1->severities()->create([
            'id'     => 'uber',
            'name'   => 'Uber Giga Brutal',
            'weight' => 200
        ]);

        // Refetch from db
        $project = ProjectProxy::find($this->project1->id);

        // The project should have 2 assigned severities
        $this->assertCount(2, $project->severities);

        $issueTypesArray = $project->severities->keyBy('id')->all();
        $this->assertArrayHasKey('pointless', $issueTypesArray);
        $this->assertArrayHasKey('uber', $issueTypesArray);
    }

    public function testSeverityCanBeAssignedToMultipleProjects()
    {
        $this->createTestProjects();
        $this->createTestSeverities();

        $this->project1->severities()->save($this->low);
        $this->project1->severities()->save($this->medium);
        $this->project1->severities()->save($this->high);

        $this->project2->severities()->save($this->low);
        $this->project2->severities()->save($this->medium);
        $this->project2->severities()->save($this->high);
        $this->project2->severities()->save($this->critical);

        // Refetch a separate instance
        $project1 = ProjectProxy::find($this->project1->id);
        $project2 = ProjectProxy::find($this->project2->id);

        $this->assertCount(3, $project1->severities);
        $this->assertCount(4, $project2->severities);

        $project1Severities = $project1->severities->keyBy('id')->all();
        $this->assertArrayHasKey(self::$TEST_LOW_KEY, $project1Severities);
        $this->assertArrayHasKey(self::$TEST_MEDIUM_KEY, $project1Severities);
        $this->assertArrayHasKey(self::$TEST_HIGH_KEY, $project1Severities);

        $project2Severities = $project2->severities->keyBy('id')->all();
        $this->assertArrayHasKey(self::$TEST_LOW_KEY, $project2Severities);
        $this->assertArrayHasKey(self::$TEST_MEDIUM_KEY, $project2Severities);
        $this->assertArrayHasKey(self::$TEST_HIGH_KEY, $project2Severities);
        $this->assertArrayHasKey(self::$TEST_CRITICAL_KEY, $project2Severities);
    }

    public function testSeveritiesAreAwareOfProjectsTheyAreAssignedTo()
    {
        $this->createTestProjects();
        $this->createTestSeverities();

        $this->project1->severities()->save($this->low);
        $this->project2->severities()->save($this->low);
        $this->project2->severities()->save($this->high);

        $this->assertCount(2, $this->low->projects);
        $this->assertCount(1, $this->high->projects);

        $this->assertArrayHasKey($this->project1->id, $this->low->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->low->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->high->projects->keyBy('id')->all());
    }

    public function testSeverityCanBeRevokedFromProject()
    {
        $this->createTestProjects();
        $this->createTestSeverities();

        $this->project1->severities()->save($this->medium);
        $this->project1->severities()->save($this->high);
        $this->project1->severities()->save($this->critical);

        $this->assertCount(3, $this->project1->severities);

        $this->project1->severities()->detach($this->critical);
        // Must reload, since detach doesn't remove it from the collection
        $this->project1->load('severities');

        $this->assertCount(2, $this->project1->severities);

        $this->assertArrayNotHasKey(self::$TEST_CRITICAL_KEY, $this->project1->severities->keyBy('id')->all());
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestClients();
        $this->createTestProjects();
    }
}
