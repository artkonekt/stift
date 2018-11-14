<?php
/**
 * Contains the PredefinedPeriods class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-31
 *
 */

namespace Konekt\Stift\Models;

use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Konekt\Enum\Enum;
use Konekt\Stift\Contracts\PredefinedPeriod as PredefinedPeriodContract;

class PredefinedPeriod extends Enum implements PredefinedPeriodContract
{
    const __default     = self::CURRENT_MONTH;

    const CURRENT_MONTH = 'current_month';
    const LAST_MONTH    = 'last_month';
    const TODAY         = 'today';
    const YESTERDAY     = 'yesterday';
    const THIS_WEEK     = 'this_week';
    const LAST_WEEK     = 'last_week';
    const CURRENT_YEAR  = 'current_year';
    const PREVIOUS_YEAR = 'previous_year';

    protected static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::CURRENT_MONTH => __('Current Month'),
            self::LAST_MONTH    => __('Last Month'),
            self::TODAY         => __('Today'),
            self::YESTERDAY     => __('Yesterday'),
            self::THIS_WEEK     => __('This Week'),
            self::LAST_WEEK     => __('Last Week'),
            self::CURRENT_YEAR => __('Current Year'),
            self::PREVIOUS_YEAR    => __('Previous Year'),
        ];
    }

    public function getDatePeriod(): DatePeriod
    {
        $daily = new DateInterval('P1D');

        switch ($this->value()) {
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
            case static::CURRENT_YEAR: {
                return new DatePeriod(Carbon::now()->startOfYear(), $daily, Carbon::now()->endOfYear());
            }
            case static::PREVIOUS_YEAR: {
                return new DatePeriod(Carbon::parse('last year')->startOfYear(), $daily, Carbon::parse('last year')->endOfYear());
            }
            default: {
                throw new \InvalidArgumentException(sprintf('Unknown period `%s`', $this->value()));
            }
        }
    }
}
