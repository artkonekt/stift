<?php
/**
 * Contains the PredefinedPeriod interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-06-01
 *
 */

namespace Konekt\Stift\Contracts;

use DatePeriod;

interface PredefinedPeriod
{
    public static function reverseLookup(DatePeriod $period): ?PredefinedPeriod;

    public function getDatePeriod(): DatePeriod;
}
