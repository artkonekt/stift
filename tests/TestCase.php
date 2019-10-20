<?php
/**
 * Contains the TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 */

namespace Konekt\Stift\Tests;

use Konekt\AppShell\Providers\ModuleServiceProvider as AppShell;
use Konekt\Concord\Contracts\Concord;
use Konekt\LaravelMigrationCompatibility\LaravelMigrationCompatibilityProvider;
use Konekt\Stift\Providers\ModuleServiceProvider as Stift;
use Konekt\Concord\ConcordServiceProvider;
use Konekt\Gears\Providers\GearsServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /** @var Concord */
    protected $concord;

    public function setUp(): void
    {
        parent::setUp();

        $this->concord = $this->app->concord;

        $this->setUpDatabase($this->app);
        $this->withFactories(__DIR__ . '/factories');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class,
            GearsServiceProvider::class,
            LaravelMigrationCompatibilityProvider::class
        ];
    }

    /**
     * Set up the environment.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'test');
        $app['config']->set('database.connections.test', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);

        $app['config']->set('view.paths', [__DIR__.'/resources/views']);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        // @todo: this should be used, but fails right now: $this->loadLaravelMigrations('test');

        // Create users table, default by Laravel
        $app['db']->connection()->getSchemaBuilder()->create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->timestamps();
        });

        $this->artisan('migrate', ['--database' => 'test']);
    }

    /**
     * {@inheritdoc}
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('concord.modules', [
            AppShell::class,
            Stift::class,
        ]);
    }
}
