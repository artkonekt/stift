<?php
/**
 * Contains the Billable class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-05
 *
 */

namespace Konekt\Stift\Filters;

class Billable
{
    public function __construct(?bool $value = null)
    {
        $this->value = $value;
    }

    public function getValue(): ?bool
    {
        return $this->value;
    }

    public function getDisplayText(): string
    {
        $value = is_null($this->value) ? null : ($this->value ? 1 : 0);

        return $this->getOptions()[$value];
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
        return [
            null => __('All hours'),
            1    => __('Billable hours'),
            0    => __('Non-billable hours')
        ];
    }
}
