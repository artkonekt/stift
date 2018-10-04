<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\ProjectProxy;

trait CreatesTestProjects
{
    /** @var  Project */
    private $project1;

    /** @var  Project */
    private $project2;

    /**
     * Creates the two projects. Also creates the clients (from parent TestCase)
     */
    private function createTestProjects()
    {
        $this->project1 = ProjectProxy::create([
            'name'        => 'R Like Ruby',
            'customer_id' => $this->clientOne->id
        ])->fresh();

        $this->project2 = ProjectProxy::create([
            'name'        => 'Go Lang',
            'customer_id' => $this->clientTwo->id
        ])->fresh();
    }
}
