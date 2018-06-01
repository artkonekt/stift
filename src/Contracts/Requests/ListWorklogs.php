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

use Konekt\Concord\Contracts\BaseRequest;
use Konekt\Stift\Contracts\PredefinedPeriod;

interface ListWorklogs extends BaseRequest
{
    public function getPeriod(): PredefinedPeriod;

    public function getProjects(): array;
}
