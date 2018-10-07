<?php
/**
 * Contains the ListIssues request interface.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-07
 *
 */

namespace Konekt\Stift\Contracts\Requests;

use Konekt\Concord\Contracts\BaseRequest;

interface ListIssues extends BaseRequest
{
    public function getStatuses(): array;

    public function getProjects(): array;
}
