<?php
/**
 * Contains the ValidatesPriority trait.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-31
 *
 */

namespace Konekt\Stift\Http\Requests;

use Konekt\Gears\Facades\Settings;

trait ValidatesPriority
{
    protected function getPriorityValidationRule(): string
    {
        return sprintf(
            'sometimes|nullable|integer|min:%d|max:%d',
            Settings::get('stift.issues.min_priority'),
            Settings::get('stift.issues.max_priority')
        );
    }
}
