<?php
/**
 * Contains the CreateIssue request class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */


namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\Stift\Contracts\Requests\UpdateIssue as UpdateIssueContract;
use Konekt\Stift\Models\IssueStatusProxy;

class UpdateIssue extends FormRequest implements UpdateIssueContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'subject'       => 'sometimes|min:2|max:255',
            'project_id'    => 'sometimes|integer',
            'issue_type_id' => 'sometimes|alpha_dash',
            'severity_id'   => 'sometimes|alpha_dash',
            'status'        => ['sometimes', Rule::in(IssueStatusProxy::values())],
            'priority'      => 'sometimes|integer',
            'due_on'        => 'sometimes|date_format:Y-m-d',
            'assigned_to'   => 'sometimes|nullable|integer'
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
