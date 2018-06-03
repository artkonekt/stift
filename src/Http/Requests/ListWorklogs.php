<?php
/**
 * Contains the ListWorklogs class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-31
 *
 */

namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Konekt\Stift\Contracts\PredefinedPeriod;
use Konekt\Stift\Contracts\Requests\ListWorklogs as ListWorklogsContract;
use Konekt\Stift\Models\PredefinedPeriodProxy;

class ListWorklogs extends FormRequest implements ListWorklogsContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'projects'   => 'sometimes',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date',
            'period' => ['sometimes', Rule::in(PredefinedPeriodProxy::values())]
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

    public function getPeriod(): PredefinedPeriod
    {
        return PredefinedPeriodProxy::create($this->get('period'));
    }

    public function getProjects(): array
    {
        $projects = $this->get('projects');
        if (null === $projects) {
            return [];
        } else {
            return is_array($projects) ? $projects : [$projects];
        }
    }
}
