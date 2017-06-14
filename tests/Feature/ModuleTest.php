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


namespace Konekt\Witser\Tests\Feature;


use Konekt\Concord\Module\Kind;
use Konekt\Witser\Tests\TestCase;

class ModuleTest extends TestCase
{
    public function testModulesArePresent()
    {
        $modules = $this->concord
            ->getModules()
            ->keyBy(function($module) {
                return $module->getId();
            });

        $this->assertTrue($modules->has('konekt.witser'), 'Witser module should be registered');
        $this->assertTrue($modules->has('konekt.app_shell'), 'AppShell module should be registered');

        $this->assertTrue(
            $modules->get('konekt.witser')
                    ->getManifest()
                    ->getKind()
                    ->equals(Kind::BOX()),
            'Concord Module Type Should be a box'
        );
    }

}