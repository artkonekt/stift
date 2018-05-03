<?php
/**
 * Contains the WorklogTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-03
 *
 */

namespace Konekt\Stift\Tests\Feature;

use Konekt\Stift\Contracts\Worklog as WorklogContract;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\Worklog;
use Konekt\Stift\Models\WorklogProxy;
use Konekt\Stift\Models\WorklogState;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestIssueTypes;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestProjects;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestSeverities;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestUsers;
use Konekt\Stift\Tests\TestCase;
use Konekt\User\Contracts\User;

class WorklogTest extends TestCase
{
    use CreatesTestClients, CreatesTestUsers, CreatesTestIssueTypes, CreatesTestSeverities, CreatesTestProjects;

    public function testHasModel()
    {
        $this->assertTrue(
            $this->concord->getModelBindings()->has(WorklogContract::class),
            'The worklog model should be present in Concord'
        );
    }

    public function testModelCanBeResolvedFromInterface()
    {
        $project = $this->app->make(WorklogContract::class);

        $this->assertInstanceOf(WorklogContract::class, $project);

        // We also expect that it's the default worklog model from this package
        $this->assertInstanceOf(Worklog::class, $project);
    }

    public function testModelProxyResolvesToDefaultModel()
    {
        $this->assertEquals(Worklog::class, WorklogProxy::modelClass());
    }

    public function testWorklogCanBeCreated()
    {
        $worklog = WorklogProxy::create([
            'user_id' => $this->user1->id
        ]);

        $this->assertInstanceOf(WorklogContract::class, $worklog);
        $this->assertInstanceOf(Worklog::class, $worklog);
    }

    public function testStateFieldIsAnEnum()
    {
        $worklog = WorklogProxy::create([
            'user_id' => $this->user1->id
        ]);

        $this->assertInstanceOf(WorklogState::class, $worklog->state);
        $this->assertTrue(WorklogState::create()->equals($worklog->state));
    }

    public function testItHasAUser()
    {
        $worklog = WorklogProxy::create([
            'user_id' => $this->user2->id
        ]);

        $this->assertInstanceOf(User::class, $worklog->user);
        $this->assertEquals($this->user2->id, $worklog->user->id);
    }

    public function testItCanHaveAnIssue()
    {
        $this->createTestSeverities();
        $this->createTestIssueTypes();
        $this->createTestProjects();

        $issue = Issue::create([
            'project_id' => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id' => $this->medium->id,
            'subject' => 'My Funky Issue',
            'status' => 'new',
            'created_by' => $this->user1->id
        ]);
        $worklog = WorklogProxy::create([
            'user_id' => $this->user2->id,
            'issue_id' => $issue->id
        ]);

        $this->assertInstanceOf(Issue::class, $worklog->issue);
        $this->assertEquals($issue->id, $worklog->issue->id);
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestUsers();
        $this->createTestClients();
    }
}
