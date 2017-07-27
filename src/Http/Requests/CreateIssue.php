<?php
/**
 * Contains the UpdateIssue request class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */


namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Stift\Contracts\Requests\CreateIssue as CreateIssueContract;

class CreateIssue extends FormRequest implements CreateIssueContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'subject'       => 'required|min:2|max:255',
            'project_id'    => 'required|alpha_dash',
            'issue_type_id' => 'required|alpha_dash',
            'severity_id'   => 'required|alpha_dash',
            'status'        => 'required|alpha_dash',
            'priority'      => 'sometimes|integer',
            'due_on'        => 'sometimes|date_format:Y-m-d',
            'created_by'    => 'required|integer',
            'assigned_to'   => 'sometimes|integer'
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
