<?php

namespace Konekt\Stift\Tests\Feature\Traits;

use Konekt\Stift\Models\Severity;
use Konekt\Stift\Models\SeverityProxy;

trait CreatesTestSeverities
{
    // Severity keys
    public static $TEST_LOW_KEY      = 'low';
    public static $TEST_MEDIUM_KEY   = 'medium';
    public static $TEST_HIGH_KEY     = 'high';
    public static $TEST_CRITICAL_KEY = 'critical';

    /** @var  Severity */
    private $low;

    /** @var  Severity */
    private $medium;

    /** @var  Severity */
    private $high;

    /** @var  Severity */
    private $critical;


    /**
     * Creates the 4 local test severities: low, medium, high, critical
     */
    private function createTestSeverities()
    {
        $this->low = SeverityProxy::create([
            'id'     => self::$TEST_LOW_KEY,
            'name'   => 'Low',
            'weight' => 1
        ]);

        $this->medium = SeverityProxy::create([
            'id'     => self::$TEST_MEDIUM_KEY,
            'name'   => 'Medium',
            'weight' => 5
        ]);

        $this->high = SeverityProxy::create([
            'id'     => self::$TEST_HIGH_KEY,
            'name'   => 'High',
            'weight' => 8
        ]);

        $this->critical = SeverityProxy::create([
            'id'     => self::$TEST_CRITICAL_KEY,
            'name'   => 'Critical',
            'weight' => 10
        ]);
    }
}
