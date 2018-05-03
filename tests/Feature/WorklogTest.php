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
use Konekt\Stift\Tests\Feature\Traits\CreatesTestUsers;
use Konekt\Stift\Tests\TestCase;

class WorklogTest extends TestCase
{
    use CreatesTestClients, CreatesTestUsers;

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
            'user_id' => $this->user1
        ]);

        $this->assertInstanceOf(WorklogContract::class, $worklog);
        $this->assertInstanceOf(Worklog::class, $worklog);
    }

    public function setUp()
    {
        parent::setUp();

        $this->createTestUsers();
        $this->createTestClients();
    }
}
