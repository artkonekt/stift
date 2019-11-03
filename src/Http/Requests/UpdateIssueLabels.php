<?php
/**
 * Contains the UpdateIssueLabels class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Konekt\Stift\Contracts\Requests\UpdateIssueLabels as UpdateIssueLabelsContract;

class UpdateIssueLabels extends FormRequest implements UpdateIssueLabelsContract
{
    public function rules()
    {
        return [
            'issue_id' => 'int|exists:issues,id',
            'labels'   => 'sometimes|array'
        ];
    }

    public function getLabelIds(): array
    {
        return $this->get('labels') ?: [];
    }

    public function authorize()
    {
        return $this->route('issue')->visibleFor(Auth::user());
    }
}
