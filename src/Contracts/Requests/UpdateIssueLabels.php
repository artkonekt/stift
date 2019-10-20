<?php
/**
 * Contains the UpdateIssueLabels interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Contracts\Requests;

use Konekt\Concord\Contracts\BaseRequest;

interface UpdateIssueLabels extends BaseRequest
{
    public function getLabelIds(): array;
}
