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


namespace Konekt\Witser\Tests\Feature;


use Konekt\Witser\Contracts\Project as ProjectContract;
use Konekt\Witser\Models\Project;
use Konekt\Witser\Models\ProjectProxy;
use Konekt\Witser\Tests\TestCase;

class ProjectTest extends TestCase
{

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
            'id'   => 'bells',
            'name' => 'Bells',
            'client_id' => $this->clientOne->id
        ]);

        $this->assertEquals('Bells', $project->name);
        $this->assertEquals('bells', $project->id);
        $this->assertEquals($this->clientOne->id, $project->client_id);
    }

    public function testProjectIdMustBeUnique()
    {
        $this->createTestClients();

        $project1 = ProjectProxy::create([
            'id'   => 'bells',
            'name' => 'Bells',
            'client_id' => $this->clientOne->id
        ])->fresh();

        $this->assertEquals('bells', $project1->id);

        $this->expectException(\PDOException::class);
        $this->expectExceptionMessageRegExp('/Integrity constraint violation/');

        ProjectProxy::create([
            'id'   => 'bells',
            'name' => 'Bells 2',
            'client_id' => $this->clientOne->id
        ]);
    }

}