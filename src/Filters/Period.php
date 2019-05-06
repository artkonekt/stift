<?php
/**
 * Contains the Period class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-05
 *
 */

namespace Konekt\Stift\Filters;

use DatePeriod;
use DatePeriodPeriod;
use Konekt\Stift\Models\PredefinedPeriodProxy;

class Period
{
    private $value;

    public function __construct(?DatePeriod $value = null)
    {
        $this->value = $value;
    }

    public function getValue(): DatePeriod
    {
        return $this->value;
    }

    public function isNotDefined(): bool
    {
        return is_null($this->value);
    }

    public function isDefined(): bool
    {
        return !$this->isNotDefined();
    }

    public function getOptions(): array
    {
        return PredefinedPeriodProxy::choices();
    }
}
