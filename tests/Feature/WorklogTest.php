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
use Konekt\Stift\Models\Worklog;
use Konekt\Stift\Models\WorklogProxy;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\TestCase;

class WorklogTest extends TestCase
{
    use CreatesTestClients;

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

    public function stestWorklogCanBeCreated()
    {
        $project = WorklogProxy::create([
            'name' => 'Bells',
            'customer_id' => $this->clientOne->id
        ]);

        $this->assertEquals('Bells', $project->name);
        $this->assertEquals($this->clientOne->id, $project->customer_id);
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestData();
    }

    private function createTestData()
    {
        $this->createTestClients();

    }
}
