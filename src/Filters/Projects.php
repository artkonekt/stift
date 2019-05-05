<?php
/**
 * Contains the Projects class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-05
 *
 */

namespace Konekt\Stift\Filters;

use Illuminate\Contracts\Auth\Authenticatable;
use Konekt\Stift\Models\ProjectProxy;

class Projects
{
    /** @var array */
    private $value;

    /** @var Authenticatable */
    private $user;

    public function __construct(Authenticatable $user, array $value = [])
    {
        $this->value = $value;
        $this->user  = $user;
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

    public function getOptions(): array
    {
        return [
            null => __('All projects'),
        ] +
        ProjectProxy::forUser($this->user)->get()->sortBy('name')->pluck('name', 'id')->all();
    }
}
