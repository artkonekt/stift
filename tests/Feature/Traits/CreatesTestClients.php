<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\Customer\Models\CustomerProxy;

trait CreatesTestClients
{
    protected $clientOne;
    protected $clientTwo;
    protected $clientThree;

    protected function createTestClients()
    {
        $this->clientOne   = CustomerProxy::create(['company_name' => 'Client 1']);
        $this->clientTwo   = CustomerProxy::create(['company_name' => 'Client 2']);
        $this->clientThree = CustomerProxy::create(['company_name' => 'Client 3']);
    }
}
