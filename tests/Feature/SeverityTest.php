<?php
/**
 * Contains the SeverityTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Witser\Tests\Feature;


use Konekt\Witser\Contracts\Severity as SeverityContract;
use Konekt\Witser\Models\Severity;
use Konekt\Witser\Models\SeverityProxy;
use Konekt\Witser\Tests\TestCase;

class SeverityTest extends TestCase
{
    public function testHasModel()
    {
        $this->assertTrue(
            $this->concord->getModelBindings()->has(SeverityContract::class),
            'The `severity` model should be present in Concord'
        );
    }

    public function testModelCanBeResolvedFromInterface()
    {
        $type = $this->app->make(SeverityContract::class);

        $this->assertInstanceOf(SeverityContract::class, $type);

        // We also expect that it's the default model from this package
        $this->assertInstanceOf(Severity::class, $type);
    }

    public function testModelProxyResolvesToDefaultModel()
    {
        $this->assertEquals(Severity::class, SeverityProxy::modelClass());
    }

    public function testIssueTypesCanBeCreated()
    {
        $critical = SeverityProxy::create([
            'id'     => 'critical',
            'name'   => 'Critical',
            'weight' => 100
        ]);

        $this->assertEquals('Critical', $critical->name);
        $this->assertEquals('critical', $critical->id);

        $normal = SeverityProxy::create([
            'id'     => 'normal',
            'name'   => 'Normal',
            'weight' => 10
        ])->fresh();

        $this->assertEquals('Normal', $normal->name);
        $this->assertEquals('normal', $normal->id);
    }

    public function testIssueTypeIdMustBeUnique()
    {
        $low = SeverityProxy::create([
            'id'     => 'low',
            'name'   => 'Low Severity',
            'weight' => 3
        ]);

        $this->assertEquals('low', $low->id);

        $this->expectException(\PDOException::class);
        $this->expectExceptionMessageRegExp('/Integrity constraint violation/');

        SeverityProxy::create([
            'id'   => 'low',
            'name' => 'Low 2',
            'weight' => 3
        ]);
    }

}