<?php
/**
 * Contains the IssueStatus enum class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-06
 *
 */

namespace Konekt\Stift\Models;

use Konekt\Enum\Enum;
use Konekt\Stift\Contracts\IssueStatus as IssueStatusContract;

class IssueStatus extends Enum implements IssueStatusContract
{
    const __default = self::TODO;

    const TODO        = 'todo';
    const IN_PROGRESS = 'in-progress';
    const DONE        = 'done';

    protected static $labels = [];

    public static function getOpenStatuses(): array
    {
        return [self::TODO, self::IN_PROGRESS];
    }

    public function isOpen(): bool
    {
        return in_array($this->value, static::getOpenStatuses());
    }

    protected static function boot()
    {
        static::$labels = [
            self::TODO        => __('Todo'),
            self::IN_PROGRESS => __('In Progress'),
            self::DONE        => __('Done')
        ];
    }
}
