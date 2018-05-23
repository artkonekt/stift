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
    const CURRENT_MONTH = 'current_month';
    const LAST_MONTH    = 'last_month';
    const TODAY         = 'today';
    const YESTERDAY     = 'yesterday';
    const THIS_WEEK     = 'this_week';
    const LAST_WEEK     = 'last_week';

    /** @var DatePeriod */
    protected $period;

    public function __construct(DatePeriod $period)
    {
        $this->period = $period;
    }

    protected static function getPeriodFromString($period): DatePeriod
    {
        $daily = new DateInterval('P1D');

        switch($period) {
            case static::CURRENT_MONTH: {
                return new DatePeriod(Carbon::now()->startOfMonth(), $daily, Carbon::now()->endOfMonth());
            }
            case static::LAST_MONTH: {
                return new DatePeriod(Carbon::parse('last month')->startOfMonth(), $daily, Carbon::parse('last month')->endOfMonth());
            }
            case static::THIS_WEEK: {
                return new DatePeriod(Carbon::now()->startOfWeek(), $daily, Carbon::now()->endOfWeek());
            }
            case static::LAST_WEEK: {
                return new DatePeriod(Carbon::parse('last week')->startOfWeek(), $daily, Carbon::parse('last week')->endOfWeek());
            }
            case static::TODAY: {
                return new DatePeriod(Carbon::now()->startOfDay(), $daily, Carbon::now()->endOfDay());
            }
            case static::YESTERDAY: {
                return new DatePeriod(Carbon::parse('yesterday')->startOfDay(), $daily, Carbon::parse('yesterday')->endOfDay());
            }
            default: {
                throw new \InvalidArgumentException(sprintf('Invalid period `%s`', $period));
            }
        }
    }
}
