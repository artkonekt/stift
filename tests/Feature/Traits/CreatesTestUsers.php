<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\AppShell\Models\User;
use Konekt\User\Models\UserProxy;

trait CreatesTestUsers
{
    // Test Users
    static $TEST_USER1_EMAIL   = 'kube@rnetes.io';
    static $TEST_USER2_EMAIL   = 'java@sun.com';

    /** @var  User */
    private $user1;

    /** @var  User */
    private $user2;

    /**
     * Creates the 2 test users
     */
    private function createTestUsers()
    {
        $this->user1 = UserProxy::create(['email' => self::$TEST_USER1_EMAIL]);
        $this->user2 = UserProxy::create(['email' => self::$TEST_USER2_EMAIL]);
    }
}
