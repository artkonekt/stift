<?php
/**
 * Contains the IssueStatus interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-06
 *
 */

namespace Konekt\Stift\Contracts;

interface IssueStatus
{
    public static function getOpenStatuses(): array;

    public function isOpen(): bool;
}
