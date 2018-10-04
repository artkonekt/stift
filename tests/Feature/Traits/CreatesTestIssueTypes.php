<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\IssueTypeProxy;

trait CreatesTestIssueTypes
{
    // Issue Type keys
    public static $TEST_BUG_KEY      = 'bug';
    public static $TEST_TASK_KEY     = 'task';
    public static $TEST_TICKET_KEY   = 'ticket';

    /** @var  IssueType */
    private $bug;

    /** @var  IssueType */
    private $task;

    /** @var  IssueType */
    private $ticket;

    /**
     * Creates the 3 local test issue types: bug, task, ticket
     */
    private function createTestIssueTypes()
    {
        $this->bug = IssueTypeProxy::create([
            'id'   => self::$TEST_BUG_KEY,
            'name' => 'Bug'
        ]);

        $this->ticket = IssueTypeProxy::create([
            'id'   => self::$TEST_TICKET_KEY,
            'name' => 'Ticket'
        ]);

        $this->task = IssueTypeProxy::create([
            'id'   => self::$TEST_TASK_KEY,
            'name' => 'Task'
        ]);
    }
}
