<?php
/**
 * Contains the Users class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-05
 *
 */

namespace Konekt\Stift\Filters;

use Konekt\User\Models\UserProxy;

class Users
{
    private $value;

    public function __construct(array $value = [])
    {
        $this->value = $value;
    }

    public function getValue(): array
    {
        return $this->value;
    }

    public function isNotDefined(): bool
    {
        return empty($this->value);
    }

    public function isDefined(): bool
    {
        return !$this->isNotDefined();
    }

    public function getDisplayText(): string
    {
        return implode(', ', $this->value);
    }

    public function getOptions(): array
    {
        return [
           null => __('All users'),
        ] + UserProxy::active()->get()->pluck('name', 'id')->all();
    }
}
