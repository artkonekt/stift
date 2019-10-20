<?php
/**
 * Contains the Label interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Contracts;

use Konekt\User\Contracts\User;

interface Label
{
    public function getTitle(): string;

    public function getColor(): string;

    public function colorAsHex(): string;

    public function visibleFor(User $user): bool;
}
