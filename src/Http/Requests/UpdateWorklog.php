<?php
/**
 * Contains the UpdateWorklog request class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-04
 */

namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\AppShell\Http\Requests\HasPermissions;
use Konekt\Stift\Contracts\Requests\UpdateWorklog as UpdateWorklogContract;
use Konekt\Stift\Models\WorklogStateProxy;

class UpdateWorklog extends FormRequest implements UpdateWorklogContract
{
    use HasPermissions;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'state'      => ['sometimes', Rule::in(WorklogStateProxy::values())],
            'started_at' => 'sometimes|date',
            'duration'   => 'sometimes|integer'
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }
}
