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


use Konekt\Stift\Contracts\IssueType;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Models\IssueTypeProxy;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Tests\TestCase;

class ProjectIssueTypesTest extends TestCase
{
    const TEST_EPIC_KEY     = 'epic';
    const TEST_STORY_KEY    = 'story';
    const TEST_TASK_KEY     = 'task';

    /** @var  Project */
    private $project1;

    /** @var  Project */
    private $project2;

    /** @var  IssueType */
    private $epic;

    /** @var  IssueType */
    private $story;

    /** @var  IssueType */
    private $task;

    public function testIssueTypeCanBeAssignedToProject()
    {
        $this->createTestProjects();

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
        $this->createTestProjects();

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
        $this->createTestProjects();
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project1->issueTypes()->save($this->story);

        $this->project2->issueTypes()->save($this->epic);
        $this->project2->issueTypes()->save($this->story);
        $this->project2->issueTypes()->save($this->task);

        // Refetch a separate instance
        $project1 = ProjectProxy::find($this->project1->id);
        $project2 = ProjectProxy::find($this->project2->id);

        $this->assertCount(2, $project1->issueTypes);
        $this->assertCount(3, $project2->issueTypes);

        $project1Types = $project1->issueTypes->keyBy('id')->all();
        $this->assertArrayHasKey(self::TEST_TASK_KEY, $project1Types);
        $this->assertArrayHasKey(self::TEST_STORY_KEY, $project1Types);

        $project2Types = $project2->issueTypes->keyBy('id')->all();
        $this->assertArrayHasKey(self::TEST_TASK_KEY, $project2Types);
        $this->assertArrayHasKey(self::TEST_STORY_KEY, $project2Types);
        $this->assertArrayHasKey(self::TEST_EPIC_KEY, $project2Types);
    }

    public function testIssueTypesAreAwareOfProjectsTheyAreAssignedTo()
    {
        $this->createTestProjects();
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project2->issueTypes()->save($this->task);
        $this->project2->issueTypes()->save($this->epic);

        $this->assertCount(2, $this->task->projects);
        $this->assertCount(1, $this->epic->projects);

        $this->assertArrayHasKey($this->project1->id, $this->task->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->task->projects->keyBy('id')->all());
        $this->assertArrayHasKey($this->project2->id, $this->epic->projects->keyBy('id')->all());
    }

    public function testIssueTypeCanBeRevokedFromProject()
    {
        $this->createTestProjects();
        $this->createTestIssueTypes();

        $this->project1->issueTypes()->save($this->task);
        $this->project1->issueTypes()->save($this->epic);
        $this->project1->issueTypes()->save($this->story);

        $this->assertCount(3, $this->project1->issueTypes);

        $this->project1->issueTypes()->detach($this->epic);
        // Must reload, since detach doesn't remove it from the collection
        $this->project1->load('issueTypes');

        $this->assertCount(2, $this->project1->issueTypes);

        $this->assertArrayNotHasKey(self::TEST_EPIC_KEY, $this->project1->issueTypes->keyBy('id')->all());
    }

    /**
     * Creates clients (with base test case) and two projects (local to this class)
     */
    protected function createTestProjects()
    {
        $this->createTestClients();

        $this->project1 = ProjectProxy::create([
            'name'      => 'Microsoft',
            'customer_id' => $this->clientOne->id
        ])->fresh();

        $this->project2 = ProjectProxy::create([
            'name'      => 'Atlassian',
            'customer_id' => $this->clientTwo->id
        ])->fresh();
    }

    /**
     * Creates the 3 local test issue types: epic, story, task
     */
    protected function createTestIssueTypes()
    {
        $this->epic = IssueTypeProxy::create([
            'id'   => self::TEST_EPIC_KEY,
            'name' => 'Epic Shit'
        ]);

        $this->story = IssueTypeProxy::create([
            'id'   => self::TEST_STORY_KEY,
            'name' => 'Story'
        ]);

        $this->task = IssueTypeProxy::create([
            'id'   => self::TEST_TASK_KEY,
            'name' => 'Task'
        ]);

    }

}
