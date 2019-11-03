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
use Illuminate\Support\Carbon;
use Konekt\Stift\Contracts\PredefinedPeriod;
use Konekt\Stift\Models\PredefinedPeriodProxy;

class Period
{
    private $value;

    public function __construct(?DatePeriod $value = null)
    {
        $this->value = $value;
    }

    public function getValue(): string
    {
        $predefined = $this->getPredefinedValue($this->value);

        return $predefined ? $predefined->value() : $this->datePeriodToString($this->value);
    }

    public function getDatePeriod(): ?DatePeriod
    {
        return $this->value;
    }

    public function getDisplayText(): string
    {
        if (null === $this->value) {
            return __('All the time');
        }

        if ($this->isExactlyOneDay($this->value)) {
            return $this->value->start->format('M d, Y');
        }

        if ($this->isExactlyOneMonth($this->value)) {
            return $this->value->start->format('M Y');
        }

        if ($this->isExactlyOneYear($this->value)) {
            return __('Entire :year', ['year' => $this->value->start->format('Y')]);
        }

        $startFormat = Carbon::instance($this->value->start)->isSameYear($this->value->end) ? 'M d' : 'M d, Y';

        return sprintf('%s - %s',
            $this->value->start->format($startFormat),
            $this->value->end->format('M d, Y')
        );
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
        $result = PredefinedPeriodProxy::choices();

        if (null === $this->getPredefinedValue($this->value)) {
            $customValue = $this->datePeriodToString($this->value);
            $result[$customValue] = $customValue;
        }

        return $result;
    }

    private function getPredefinedValue(DatePeriod $period): ?PredefinedPeriod
    {
        return PredefinedPeriodProxy::reverseLookup($period);
    }

    private function datePeriodToString(DatePeriod $period): string
    {
        if ($this->isExactlyOneDay($period)) {
            return $period->start->format('Y-m-d');
        } elseif ($this->isExactlyOneMonth($period)) {
            return $period->start->format('Y-m');
        } elseif ($this->isExactlyOneYear($period)) {
            return $period->start->format('Y');
        }

        return sprintf('%s-%s',
            $period->start->format('Y-m-d'),
            $period->end->format('Y-m-d')
        );
    }

    private function isExactlyOneDay(DatePeriod $period): bool
    {
        $start = Carbon::instance($period->start);
        $end = Carbon::instance($period->end);

        return
            $start->isStartOfDay()
            &&
            $end->isEndOfDay()
            &&
            $start->isSameDay($end);
    }

    private function isExactlyOneMonth(DatePeriod $period): bool
    {
        $start = Carbon::instance($period->start);
        $end = Carbon::instance($period->end);

        return
            $start->copy()->startOfMonth()->eq($start)
            &&
            $end->copy()->endOfMonth()->eq($end)
            &&
            $start->isSameMonth($end);
    }

    private function isExactlyOneYear(DatePeriod $period): bool
    {
        $start = Carbon::instance($period->start);
        $end = Carbon::instance($period->end);

        return
            $start->copy()->startOfYear()->eq($start)
            &&
            $end->copy()->endOfYear()->eq($end)
            &&
            $start->isSameYear($end);
    }
}
