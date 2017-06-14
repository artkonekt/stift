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


namespace Konekt\Witser\Tests\Feature;


use Konekt\Witser\Contracts\Project;
use Konekt\Witser\Contracts\Severity;
use Konekt\Witser\Models\ProjectProxy;
use Konekt\Witser\Models\SeverityProxy;
use Konekt\Witser\Tests\TestCase;

class ProjectSeveritiesTest extends TestCase
{
    const TEST_PROJECT1_KEY = 'volvo';
    const TEST_PROJECT2_KEY = 'hyundai';

    const TEST_LOW_KEY      = 'low';
    const TEST_MEDIUM_KEY   = 'medium';
    const TEST_HIGH_KEY     = 'high';
    const TEST_CRITICAL_KEY = 'critical';

    /** @var  Project */
    private $project1;

    /** @var  Project */
    private $project2;

    /** @var  Severity */
    private $low;

    /** @var  Severity */
    private $medium;

    /** @var  Severity */
    private $high;

    /** @var  Severity */
    private $critical;

    public function testSeverityCanBeAssignedToProject()
    {
        $this->createTestProjects();

        $this->project1->severities()->create([
            'id'     => 'galactic',
            'name'   => 'Galaxy Is About To Die',
            'weight' => PHP_INT_MAX // HAHAHA I'm funny
        ]);

        // Refetch from db
        $project1 = ProjectProxy::find(self::TEST_PROJECT1_KEY);

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
        $project = ProjectProxy::find(self::TEST_PROJECT1_KEY);

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
        $project1 = ProjectProxy::find(self::TEST_PROJECT1_KEY);
        $project2 = ProjectProxy::find(self::TEST_PROJECT2_KEY);

        $this->assertCount(3, $project1->severities);
        $this->assertCount(4, $project2->severities);

        $project1Severities = $project1->severities->keyBy('id')->all();
        $this->assertArrayHasKey(self::TEST_LOW_KEY, $project1Severities);
        $this->assertArrayHasKey(self::TEST_MEDIUM_KEY, $project1Severities);
        $this->assertArrayHasKey(self::TEST_HIGH_KEY, $project1Severities);

        $project2Severities = $project2->severities->keyBy('id')->all();
        $this->assertArrayHasKey(self::TEST_LOW_KEY, $project2Severities);
        $this->assertArrayHasKey(self::TEST_MEDIUM_KEY, $project2Severities);
        $this->assertArrayHasKey(self::TEST_HIGH_KEY, $project2Severities);
        $this->assertArrayHasKey(self::TEST_CRITICAL_KEY, $project2Severities);
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

        $this->assertArrayHasKey(self::TEST_PROJECT1_KEY, $this->low->projects->keyBy('id')->all());
        $this->assertArrayHasKey(self::TEST_PROJECT2_KEY, $this->low->projects->keyBy('id')->all());
        $this->assertArrayHasKey(self::TEST_PROJECT2_KEY, $this->high->projects->keyBy('id')->all());
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

        $this->assertArrayNotHasKey(self::TEST_CRITICAL_KEY, $this->project1->severities->keyBy('id')->all());
    }

    /**
     * Creates clients (with base test case) and two projects (local to this class)
     */
    protected function createTestProjects()
    {
        $this->createTestClients();

        $this->project1 = ProjectProxy::create([
            'id'        => self::TEST_PROJECT1_KEY,
            'name'      => 'Volvo, Sweden',
            'client_id' => $this->clientOne->id
        ]);

        $this->project2 = ProjectProxy::create([
            'id'        => self::TEST_PROJECT2_KEY,
            'name'      => 'Hyundai, Korea',
            'client_id' => $this->clientTwo->id
        ]);
    }

    /**
     * Creates the 4 local test severities: low, medium, high, critical
     */
    protected function createTestSeverities()
    {
        $this->low = SeverityProxy::create([
            'id'     => self::TEST_LOW_KEY,
            'name'   => 'Low',
            'weight' => 1
        ]);

        $this->medium = SeverityProxy::create([
            'id'     => self::TEST_MEDIUM_KEY,
            'name'   => 'Medium',
            'weight' => 5
        ]);

        $this->high = SeverityProxy::create([
            'id'     => self::TEST_HIGH_KEY,
            'name'   => 'High',
            'weight' => 8
        ]);

        $this->critical = SeverityProxy::create([
            'id'     => self::TEST_CRITICAL_KEY,
            'name'   => 'Critical',
            'weight' => 10
        ])->fresh();

    }

}