<?php
/**
 * Contains the ListIssues request class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-07
 *
 */

namespace Konekt\Stift\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Konekt\Stift\Contracts\Requests\ListIssues as ListIssuesContract;
use Konekt\Stift\Exceptions\UnknownFilterException;
use Konekt\Stift\Models\IssueStatusProxy;

class ListIssues extends FormRequest implements ListIssuesContract
{
    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            'status'   => 'sometimes|nullable|alpha_dash',
            'projects' => 'sometimes',
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

    public function getProjects(): array
    {
        $projects = $this->get('projects');

        if (null === $projects) {
            $result = [];
        } else {
            $result = is_array($projects) ? $projects : [$projects];
        }

        // Remove invalid entries
        return array_filter($result, function ($id) {
            return is_int($id) || ctype_digit($id);
        });
    }

    public function getStatuses(): array
    {
        $status = $this->get('status');

        if (null === $status) {
            return [];
        } elseif (IssueStatusProxy::has($status)) {
            return [$status];
        } else {
            return $this->resolveCustomStatus($status);
        }
    }

    protected function resolveCustomStatus(string $status): array
    {
        switch ($status) {
            case 'open_issues':
                return IssueStatusProxy::getOpenStatuses();
                break;
        }

        throw new UnknownFilterException(__('Unknown status filter `:name`', ['name' => $status]));
    }
}
