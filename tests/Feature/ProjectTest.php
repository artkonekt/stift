<?php
/**
 * Contains the ProjectTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Tests\Feature;

use Konekt\Stift\Contracts\Project as ProjectContract;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Tests\Feature\Traits\CreatesTestClients;
use Konekt\Stift\Tests\TestCase;

class ProjectTest extends TestCase
{
    use CreatesTestClients;

    public function testHasModel()
    {
        $this->assertTrue(
            $this->concord->getModelBindings()->has(ProjectContract::class),
            'The project model should be present in Concord'
        );
    }

    public function testModelCanBeResolvedFromInterface()
    {
        $project = $this->app->make(ProjectContract::class);

        $this->assertInstanceOf(ProjectContract::class, $project);

        // We also expect that it's the default project model from this package
        $this->assertInstanceOf(Project::class, $project);
    }

    public function testModelProxyResolvesToDefaultModel()
    {
        $this->assertEquals(Project::class, ProjectProxy::modelClass());
    }

    public function testProjectCanBeCreated()
    {
        $this->createTestClients();

        $project = ProjectProxy::create([
            'name'        => 'Bells',
            'customer_id' => $this->clientOne->id
        ]);

        $this->assertEquals('Bells', $project->name);
        $this->assertEquals($this->clientOne->id, $project->customer_id);
    }
}
