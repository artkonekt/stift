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


namespace Konekt\Witser\Tests\Feature;


use Konekt\AppShell\Models\User;
use Konekt\User\Models\UserProxy;
use Konekt\Witser\Models\IssueProxy;
use Konekt\Witser\Models\IssueType;
use Konekt\Witser\Models\IssueTypeProxy;
use Konekt\Witser\Models\Project;
use Konekt\Witser\Models\ProjectProxy;
use Konekt\Witser\Models\Severity;
use Konekt\Witser\Models\SeverityProxy;
use Konekt\Witser\Tests\TestCase;

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