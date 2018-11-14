<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueStatus;

trait CreatesTestIssues
{
    /** @var Issue */
    protected $taskMedium;

    /** @var Issue */
    protected $taskLow;

    /** @var Issue */
    protected $bugCritical;

    public function createTestIssues()
    {
        $this->taskMedium = Issue::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->medium->id,
            'subject'       => 'Medium Priority Task',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id
        ]);

        $this->taskLow = Issue::create([
            'project_id'    => $this->project1->id,
            'issue_type_id' => $this->task->id,
            'severity_id'   => $this->low->id,
            'subject'       => 'Low Priority Task',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user1->id
        ]);

        $this->bugCritical = Issue::create([
            'project_id'    => $this->project2->id,
            'issue_type_id' => $this->bug->id,
            'severity_id'   => $this->critical->id,
            'subject'       => 'Critical Bug',
            'status'        => IssueStatus::defaultValue(),
            'created_by'    => $this->user2->id
        ]);
    }
}
