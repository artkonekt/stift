<?php
/**
 * Contains the IssueTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Tests\Feature;

use Carbon\Carbon;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueStatus;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestIssueTypes;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestProjects;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestSeverities;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestUsers;
use Konekt\Stift\Models\IssueProxy;
use Konekt\Stift\Tests\TestCase;

class IssueTest extends TestCase
{
    use CreatesTestIssueTypes, CreatesTestSeverities, CreatesTestUsers, CreatesTestClients, CreatesTestProjects;

    public function setUp()
    {
        parent::setUp();

        $this->createTestData();
    }

    public function testIssueCanBeCreated()
    {
        $criticalBug = IssueProxy::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->bug->id,
            'severity_id'   => $this->critical->id,
            'subject'       => 'Whoa, this is a critical bug',
            'description'   => 'A fly peed on the vending machine',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id
        ]);

        $this->assertGreaterThan(0, $criticalBug->id);
    }

    public function testIssueHoldsAssociatedEntities()
    {
        $mediumTicket = IssueProxy::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->ticket->id,
            'severity_id'   => $this->medium->id,
            'subject'       => 'We need some more money',
            'description'   => 'Money is never enough. Could you please send us even more?',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id,
            'assigned_to'   => $this->user2->id
        ]);

        $this->assertEquals($this->project1->id, $mediumTicket->project->id,
            'Issue\'s associated project should be obtained via the `project` property'
        );

        $this->assertEquals($this->ticket->id, $mediumTicket->issueType->id,
            'Issue\'s associated type should be obtained via the `issueType` property'
        );

        $this->assertEquals($this->medium->id, $mediumTicket->severity->id,
            'Issue\'s associated severity should be obtained via the `severity` property'
        );

        $this->assertEquals($this->user1->id, $mediumTicket->createdBy->id,
            'Issue\'s associated user that created the issue should be obtained via the `createdBy` property'
        );

        $this->assertEquals($this->user2->id, $mediumTicket->assignedTo->id,
            'Issue\'s assigned user should be available via the `assignedTo` property'
        );
    }

    public function testIssueDueOnField()
    {
        $lowTask = IssueProxy::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->low->id,
            'subject'       => 'Buy some cranberries',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id
        ]);

        $this->assertNull($lowTask->due_on, 'Issue without due date should return null');

        $mediumTask = IssueProxy::create([
            'project_id'    => $this->project2->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->medium->id,
            'subject'       => 'Get some birthday gifts for Louis',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user2->id,
            'due_on'        => '2017-09-26 23:59:59'
        ]);

        $this->assertInstanceOf(Carbon::class, $mediumTask->due_on);
        $this->assertTrue(
            $mediumTask->due_on->eq(
                Carbon::parse('2017-09-26 23:59:59')
            ),
            'Due date should be specified via string'
        );

        $highTask = IssueProxy::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->high->id,
            'subject'       => 'Go Shopping NOW!!',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id,
            'due_on'        => Carbon::parse('2017-06-11 12:00:00')
        ])->fresh();

        $this->assertInstanceOf(Carbon::class, $highTask->due_on);
        $this->assertTrue(
            $highTask->due_on->eq(
                Carbon::parse('2017-06-11 12:00:00')
            ),
            'Due date should be set with Carbon object'
        );
    }

    public function testMarkdownDescriptionAsHtml()
    {
        $issue = IssueProxy::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->low->id,
            'subject'       => 'My Description Is Markdown',
            'status'        => IssueStatus::defaultValue(),
            'description'   => "# Hello\n\nWhat's up?",
            'created_by'    => $this->user1->id
        ]);


        $this->assertContains('<h1>Hello</h1>', $issue->getMarkdownDescriptionAsHtml());
        $this->assertContains("<p>What's up?</p>", $issue->getMarkdownDescriptionAsHtml());
    }

    /** @test */
    public function issues_can_be_sorted_by_priority()
    {
        factory(Issue::class)->create(['priority' => 1, 'subject' => 'Task 1']);
        factory(Issue::class)->create(['priority' => 3, 'subject' => 'Task 3']);
        factory(Issue::class)->create(['priority' => 2, 'subject' => 'Task 2']);

        $issues = Issue::sort()->get()->pluck('subject')->toArray();
        $this->assertEquals(['Task 1', 'Task 2', 'Task 3'], $issues);
    }

    /** @test */
    public function issues_can_be_reverse_sorted_by_priority()
    {
        factory(Issue::class)->create(['priority' => 54, 'subject' => 'Task 3']);
        factory(Issue::class)->create(['priority' => 77, 'subject' => 'Task 1']);
        factory(Issue::class)->create(['priority' => 55, 'subject' => 'Task 2']);
        factory(Issue::class)->create(['priority' => 22, 'subject' => 'Task 4']);

        $issues = Issue::sortReverse()->get()->pluck('subject')->toArray();
        $this->assertEquals(['Task 1', 'Task 2', 'Task 3', 'Task 4'], $issues);
    }

    protected function createTestData()
    {
        $this->createTestUsers();
        $this->createTestIssueTypes();
        $this->createTestSeverities();
        $this->createTestClients();
        $this->createTestProjects();
    }
}
