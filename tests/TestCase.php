<?php
/**
 * Contains the TestCase class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Witser\Tests;

use Konekt\AppShell\Providers\ModuleServiceProvider as AppShell;
use Konekt\Concord\Contracts\Concord;
use Konekt\Witser\Providers\ModuleServiceProvider as Witser;
use Konekt\Concord\ConcordServiceProvider;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    /** @var  Concord */
    protected $concord;

    protected $clientOne;
    protected $clientTwo;
    protected $clientThree;

    public function setUp()
    {
        parent::setUp();

        $this->concord = $this->app->concord;

        $this->setUpDatabase($this->app);
    }

    protected function createTestClients()
    {
        $this->clientOne   = Client::create(['name' => 'Client 1']);
        $this->clientTwo   = Client::create(['name' => 'Client 2']);
        $this->clientThree = Client::create(['name' => 'Client 3']);
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            ConcordServiceProvider::class
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

//        $app['config']->set('database.default', 'test');
//        $app['config']->set('database.connections.test', [
//            'driver'   => 'mysql',
//            'host'     => 'localhost',
//            'database' => 'witser_test',
//            'username' => 'root',
//            'password' => 'sampler'
//        ]);

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

        // Client table faked until module gets implemented
        $app['db']->connection()->getSchemaBuilder()->create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
        });

        $this->artisan('migrate', ['--database' => 'test']);
    }

    /**
     * @inheritdoc
     */
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);
        $app['config']->set('concord.modules', [
            AppShell::class,
            Witser::class
        ]);
    }

}