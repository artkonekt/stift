<?php
/**
 * Contains the ListWorklogs request interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-31
 *
 */

namespace Konekt\Stift\Contracts\Requests;

use DatePeriod;
use Konekt\Concord\Contracts\BaseRequest;

interface ListWorklogs extends BaseRequest
{
    public function getPeriod(): DatePeriod;

    public function getUsers(): array;

    public function getProjects(): array;

    public function getBillable(): ?bool;
}
