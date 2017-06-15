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
use Konekt\AppShell\Models\User;
use Konekt\User\Models\UserProxy;
use Konekt\Stift\Models\IssueProxy;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\IssueTypeProxy;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\Severity;
use Konekt\Stift\Models\SeverityProxy;
use Konekt\Stift\Tests\TestCase;

class IssueTest extends TestCase
{
    //Project keys
    const TEST_PROJECT1_KEY = 'ruby';
    const TEST_PROJECT2_KEY = 'go';

    // Severity keys
    const TEST_LOW_KEY      = 'low';
    const TEST_MEDIUM_KEY   = 'medium';
    const TEST_HIGH_KEY     = 'high';
    const TEST_CRITICAL_KEY = 'critical';

    // Issue Type keys
    const TEST_BUG_KEY      = 'bug';
    const TEST_TASK_KEY     = 'task';
    const TEST_TICKET_KEY   = 'ticket';

    // Test Users
    const TEST_USER1_EMAIL   = 'kube@rnetes.io';
    const TEST_USER2_EMAIL   = 'java@sun.com';

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

    /** @var  IssueType */
    private $bug;

    /** @var  IssueType */
    private $task;

    /** @var  IssueType */
    private $ticket;

    /** @var  User */
    private $user1;

    /** @var  User */
    private $user2;

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
            'status'        => '',
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
            'status'        => '',
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
            'status'        => '',
            'created_by'    => $this->user1->id
        ]);

        $this->assertNull($lowTask->due_on, 'Issue without due date should return null');

        $mediumTask = IssueProxy::create([
            'project_id'    => $this->project2->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->medium->id,
            'subject'       => 'Get some birthday gifts for Louis',
            'status'        => '',
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
            'status'        => '',
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

    protected function createTestData()
    {
        $this->createTestUsers();
        $this->createTestIssueTypes();
        $this->createTestSeverities();
        $this->createTestProjects();
    }

    /**
     * Creates the two projects. Also creates the clients (from parent TestCase)
     */
    private function createTestProjects()
    {
        $this->createTestClients();

        $this->project1 = ProjectProxy::create([
            'id'        => self::TEST_PROJECT1_KEY,
            'name'      => 'R Like Ruby',
            'client_id' => $this->clientOne->id
        ])->fresh();

        $this->project2 = ProjectProxy::create([
            'id'        => self::TEST_PROJECT2_KEY,
            'name'      => 'Go Lang',
            'client_id' => $this->clientTwo->id
        ])->fresh();
    }

    /**
     * Creates the 4 local test severities: low, medium, high, critical
     */
    private function createTestSeverities()
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
        ]);

    }

    /**
     * Creates the 3 local test issue types: bug, task, ticket
     */
    private function createTestIssueTypes()
    {
        $this->bug = IssueTypeProxy::create([
            'id'   => self::TEST_BUG_KEY,
            'name' => 'Bug'
        ]);

        $this->ticket = IssueTypeProxy::create([
            'id'   => self::TEST_TICKET_KEY,
            'name' => 'Ticket'
        ]);

        $this->task = IssueTypeProxy::create([
            'id'   => self::TEST_TASK_KEY,
            'name' => 'Task'
        ]);

    }

    /**
     * Creates the 2 test users
     */
    private function createTestUsers()
    {
        $this->user1 = UserProxy::create(['email' => self::TEST_USER1_EMAIL]);
        $this->user2 = UserProxy::create(['email' => self::TEST_USER2_EMAIL]);
    }

}