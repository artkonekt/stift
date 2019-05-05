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
use Konekt\Stift\Tests\Feature\Traits\CreatesTestIssues;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestIssueTypes;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestProjects;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestSeverities;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestUsers;
use Konekt\Stift\Tests\TestCase;
use Konekt\User\Contracts\User;

class WorklogTest extends TestCase
{
    use CreatesTestClients, CreatesTestUsers, CreatesTestIssueTypes, CreatesTestSeverities, CreatesTestProjects,
        CreatesTestIssues;

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
        $this->createTestIssues();

        $worklog = WorklogProxy::create([
            'user_id'  => $this->user2->id,
            'issue_id' => $this->taskMedium->id
        ]);

        $this->assertInstanceOf(Issue::class, $worklog->issue);
        $this->assertEquals($this->taskMedium->id, $worklog->issue->id);
    }

    /**
     * @test
     */
    public function issues_have_their_related_worklogs()
    {
        $this->createTestSeverities();
        $this->createTestIssueTypes();
        $this->createTestProjects();
        $this->createTestIssues();

        $worklog1 = WorklogProxy::create([
            'user_id'     => $this->user2->id,
            'description' => 'I worked on this',
            'issue_id'    => $this->taskLow->id
        ]);

        $worklog2 = WorklogProxy::create([
            'user_id'     => $this->user2->id,
            'description' => 'I worked on it again',
            'issue_id'    => $this->taskLow->id
        ]);

        $this->assertCount(2, $this->taskLow->worklogs);
    }

    /**
     * @test
     */
    public function worklogs_can_be_created_on_issues()
    {
        $this->createTestSeverities();
        $this->createTestIssueTypes();
        $this->createTestProjects();
        $this->createTestIssues();

        $worklog = $this->bugCritical->worklogs()->create([
            'user_id'     => $this->user2->id,
            'description' => 'I worked hard'
        ])->fresh();

        $this->assertCount(1, $this->bugCritical->worklogs);
        $this->assertEquals($worklog->id, $this->bugCritical->worklogs->first()->id);
    }

    /** @test */
    public function worklogs_are_billable_by_default()
    {
        $worklog = Worklog::create(['user_id' => $this->user1->id])->fresh();

        $this->assertTrue($worklog->is_billable);
    }

    /** @test */
    public function worklogs_can_be_set_as_non_billable()
    {
        $worklog = Worklog::create([
            'user_id' => $this->user1->id,
            'is_billable' => false
        ])->fresh();

        $this->assertFalse($worklog->is_billable);

        $worklog2 = new Worklog();
        $worklog2->user_id = $this->user1->id;
        $worklog2->is_billable = false;
        $worklog2->save();

        $this->assertFalse($worklog2->fresh()->is_billable);
    }

    /** @test */
    public function worklogs_can_be_filtered_based_on_billable_flag()
    {
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => false]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => false]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => false]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => true]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => true]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => true]);
        Worklog::create(['user_id' => $this->user1->id, 'is_billable' => true]);

        $this->assertCount(7, Worklog::get());
        $this->assertCount(3, Worklog::nonBillable()->get());
        $this->assertCount(4, Worklog::billable()->get());
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestUsers();
        $this->createTestClients();
    }
}
