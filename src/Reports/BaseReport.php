<?php
/**
 * Contains the BaseReport class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-23
 *
 */

namespace Konekt\Stift\Reports;

use DateInterval;
use DatePeriod;
use Illuminate\Support\Carbon;

abstract class BaseReport
{
    /** @var DatePeriod */
    protected $period;

    public function __construct(DatePeriod $period)
    {
        $this->period = $period;
    }
}
