<?php
/**
 * Contains the IssueTypeTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Tests\Feature;


use Konekt\Stift\Contracts\IssueType as IssueTypeContract;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\IssueTypeProxy;
use Konekt\Stift\Tests\TestCase;

class IssueTypeTest extends TestCase
{
    public function testHasModel()
    {
        $this->assertTrue(
            $this->concord->getModelBindings()->has(IssueTypeContract::class),
            'The issue type model should be present in Concord'
        );
    }

    public function testModelCanBeResolvedFromInterface()
    {
        $type = $this->app->make(IssueTypeContract::class);

        $this->assertInstanceOf(IssueTypeContract::class, $type);

        // We also expect that it's the default model from this package
        $this->assertInstanceOf(IssueType::class, $type);
    }

    public function testModelProxyResolvesToDefaultModel()
    {
        $this->assertEquals(IssueType::class, IssueTypeProxy::modelClass());
    }

    public function testIssueTypesCanBeCreated()
    {
        $bug = IssueTypeProxy::create([
            'id'     => 'bug',
            'name'   => 'Bug'
        ])->fresh();

        $this->assertEquals('Bug', $bug->name);
        $this->assertEquals('bug', $bug->id);

        $task = IssueTypeProxy::create([
            'id'   => 'task',
            'name' => 'Task'
        ])->fresh();

        $this->assertEquals('Task', $task->name);
        $this->assertEquals('task', $task->id);
    }

    public function testIssueTypeIdMustBeUnique()
    {
        $defect = IssueTypeProxy::create([
            'id'   => 'defect',
            'name' => 'Defect'
        ])->fresh();

        $this->assertEquals('defect', $defect->id);

        $this->expectException(\PDOException::class);
        $this->expectExceptionMessageRegExp('/Integrity constraint violation/');

        IssueTypeProxy::create([
            'id'   => 'defect',
            'name' => 'Defect 2'
        ]);
    }

}