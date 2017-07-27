<?php
/**
 * Contains the Project interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Contracts;


use Konekt\User\Contracts\User;

interface Project
{
    /**
     * Returns whether the project is visible/accessible for a given user
     *
     * @param User $user
     *
     * @return bool
     */
    public function visibleFor(User $user);

}