<?php
/**
 * Contains the ModuleTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Tests\Feature;


use Konekt\Concord\Module\Kind;
use Konekt\Stift\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function testModulesArePresent()
    {
        $modules = $this->concord
            ->getModules(true)
            ->keyBy(function($module) {
                return $module->getId();
            });

        $this->assertTrue($modules->has('konekt.stift'), 'Stift module should be registered');
        $this->assertTrue($modules->has('konekt.client'), 'Client module should be registered');
        $this->assertTrue($modules->has('konekt.app_shell'), 'AppShell module should be registered');

        $this->assertTrue(
            $modules->get('konekt.stift')
                    ->getManifest()
                    ->getKind()
                    ->equals(Kind::BOX()),
            'Concord Module Type Should be a box'
        );
    }

}