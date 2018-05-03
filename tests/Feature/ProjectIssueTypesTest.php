<?php
/**
 * Contains the ProjectIssueTypesTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */

namespace Konekt\Stift\Tests\Feature;

use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestIssueTypes;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestProjects;
use Konekt\Stift\Tests\TestCase;

class ProjectIssueTypesTest extends TestCase
{
    use CreatesTestClients, CreatesTestProjects, CreatesTestIssueTypes;

    public function testIssueTypeCanBeAssignedToProject()
    {
        $this->project1->issueTypes()->create([
            'id' => 'bug',
            'name' => 'Microsoft Bug'
        ]);

        // Refetch from db
        $project1 = ProjectProxy::find($this->project1->id);

        // The project should only have one assigned issue type
        $this->assertCount(1, $project1->issueTypes);
        $this->assertArrayHasKey('bug',
            $project1->issueTypes->keyBy('id')->all()
        );
    }

    public function testSeveralIssueTypesCanBeAssignedToAProject()
    {
        $this->project1->issueTypes()->create([
            'id' => 'fly',
            'name' => 'Microsoft Fly Bug'
        ]);

        $this->project1->issueTypes()->create([
            'id' => 'flee',
            'name' => 'Microsoft Flee Bug'
        ]);

        // Refetch from db
        $project = ProjectProxy::find($this->project1->id);

        // The project should have 2 assigned issue types
        $this->assertCount(2, $project->issueTypes);

        $issueTypesArray = $project->issueTypes->keyBy('id')->all();
        $this->assertArrayHasKey('fly', $issueTypesArray);
        $this->assertArrayHasKey('flee', $issueTypesArray);
    }

    public function testIssueTypeCanBeAssignedToMultipleProjects()
    {
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project1->issueTypes()->save($this->ticket);

        $this->project2->issueTypes()->save($this->bug);
        $this->project2->issueTypes()->save($this->ticket);
        $this->project2->issueTypes()->save($this->task);

        // Refetch a separate instance
        $project1 = ProjectProxy::find($this->project1->id);
        $project2 = ProjectProxy::find($this->project2->id);

        $this->assertCount(2, $project1->issueTypes);
        $this->assertCount(3, $project2->issueTypes);

        $project1Types = $project1->issueTypes->keyBy('id')->all();
        $this->assertArrayHasKey(self::$TEST_TASK_KEY, $project1Types);
        $this->assertArrayHasKey(self::$TEST_TICKET_KEY, $project1Types);

        $project2Types = $project2->issueTypes->keyBy('id')->all();
        $this->assertArrayHasKey(self::$TEST_TASK_KEY, $project2Types);
        $this->assertArrayHasKey(self::$TEST_BUG_KEY, $project2Types);
        $this->assertArrayHasKey(self::$TEST_TICKET_KEY, $project2Types);
    }

    public function testIssueTypesAreAwareOfProjectsTheyAreAssignedTo()
    {
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project2->issueTypes()->save($this->task);
        $this->project2->issueTypes()->save($this->bug);

        $this->assertCount(2, $this->task->projects);
        $this->assertCount(1, $this->bug->projects);

        $this->assertArrayHasKey($this->project1->id, $this->task->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->task->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->bug->projects->keyBy('id')->all());
    }

    public function testIssueTypeCanBeRevokedFromProject()
    {
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project1->issueTypes()->save($this->bug);
        $this->project1->issueTypes()->save($this->ticket);

        $this->assertCount(3, $this->project1->issueTypes);

        $this->project1->issueTypes()->detach($this->bug);
        // Must reload, since detach doesn't remove it from the collection
        $this->project1->load('issueTypes');

        $this->assertCount(2, $this->project1->issueTypes);

        $this->assertArrayNotHasKey(self::$TEST_BUG_KEY, $this->project1->issueTypes->keyBy('id')->all());
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestClients();
        $this->createTestProjects();
    }
}
