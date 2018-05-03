<?php
/**
 * Contains the WorklogState enum class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-03
 */

namespace Konekt\Stift\Models;

use Konekt\Enum\Enum;
use Konekt\Stift\Contracts\WorklogState as WorklogStateContract;

class WorklogState extends Enum implements WorklogStateContract
{
    const __default = self::RUNNING;

    const RUNNING   = 'running';
    const PAUSED    = 'paused';
    const FINISHED  = 'finished';
    const APPROVED  = 'approved';
    const REJECTED  = 'rejected';
    const BILLED    = 'billed';

    public static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::RUNNING  => __('Running'),
            self::PAUSED   => __('Paused'),
            self::FINISHED => __('Finished'),
            self::APPROVED => __('Approved'),
            self::REJECTED => __('Rejected'),
            self::BILLED   => __('Billed')
        ];
    }
}
