<?php
/**
 * Contains the DurationHumanizer class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-04
 */

namespace Konekt\Stift\Helpers;

class DurationHumanizer
{
    private static $defaults = ['w' => 0, 'd' => 0, 'h' => 0, 'm' => 0];

    private $hoursPerDay;

    private $daysPerWeek;

    public function __construct($hoursPerDay = 6, $daysPerWeek = 5)
    {
        $this->hoursPerDay = $hoursPerDay;
        $this->daysPerWeek = $daysPerWeek;
    }

    public function secondsToHumanReadable(int $seconds, $withSeconds = false)
    {
        $minutes = $withSeconds ? intdiv($seconds, 60) : (int)round($seconds/60);
        $remSecs = $seconds % 60;

        if (0 == $minutes) {
            return sprintf('%ds', $remSecs);
        }

        $result = $this->minutesToHumanReadable($minutes);

        if ($withSeconds && $remSecs) {
            $result .= sprintf(' %ds', $remSecs);
        }

        return $result;
    }

    public function minutesToHumanReadable(int $minutes)
    {
        if (0 == $minutes) {
            return '0m';
        }

        $values = $this->parseIntToArray($minutes);

        $result = '';

        foreach(['w', 'd', 'h', 'm'] as $part) {
            if ($values[$part]) {
                $result .= sprintf('%d%s ', $values[$part], $part);
            }
        }

        return trim($result);
    }

    public function humanReadableToSeconds(string $value): int
    {
        return $this->humanReadableToMinutes($value) * 60;
    }

    public function humanReadableToMinutes(string $value): int
    {
        $parts = explode(' ', $value);

        $result = self::$defaults;

        foreach ($parts as $part) {
            if (ends_with(trim($part), 'h')) {
                $result['h'] = intval($part);
            } elseif (ends_with(trim($part), 'd')) {
                $result['d'] = intval($part);
            } elseif (ends_with(trim($part), 'm')) {
                $result['m'] = intval($part);
            }  elseif (ends_with(trim($part), 'w')) {
                $result['w'] = intval($part);
            }
        }

        return $result['m']
            + 60 * $result['h']
            + $this->hoursPerDay * 60 * $result['d']
            + $this->daysPerWeek * $this->hoursPerDay * 60 * $result['w'];
    }

    private function minsPerWeek()
    {
        return $this->daysPerWeek * $this->hoursPerDay * 60;
    }

    private function minsPerDay()
    {
        return $this->hoursPerDay * 60;
    }

    public function parseIntToArray($value)
    {
        $result = self::$defaults;

        $weeks = intval(floor($value / $this->minsPerWeek()));
        if ($weeks) {
            $result['w'] = $weeks;
            $value -= $weeks * $this->minsPerWeek();
        }

        $days = intval(floor($value / $this->minsPerDay()));
        if ($days) {
            $result['d'] = $days;
            $value -= $days * $this->minsPerDay();
        }

        $hours = intval(floor($value / 60));
        if ($hours) {
            $result['h'] = $hours;
            $value -= $hours * 60;
        }

        $result['m'] = $value;

        return $result;
    }
}
