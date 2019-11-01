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

use Carbon\Carbon;
use DatePeriod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
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
            'end_date'   => 'sometimes|date',
            //@todo: fix this shortcut
            //'period'     => ['sometimes', Rule::in(PredefinedPeriodProxy::values())]
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

    public function getPeriod(): DatePeriod
    {
        if (is_null($this->get('period')) || PredefinedPeriodProxy::has($this->get('period'))) {
            return PredefinedPeriodProxy::create($this->get('period'))->getDatePeriod();
        }

        return $this->parsePeriod($this->get('period'));
    }

    public function getUsers(): array
    {
        $users = $this->get('users');

        if (null === $users) {
            $result = [];
        } else {
            $result = is_array($users) ? $users : [$users];
        }

        // Remove invalid entries
        return array_filter($result, function ($id) {
            return is_int($id) || ctype_digit($id);
        });
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

    public function getBillable(): ?bool
    {
        if (!$this->has('billable') || is_null($this->get('billable'))) {
            return null;
        }

        return (bool) $this->get('billable');
    }

    public function parsePeriod($period): DatePeriod
    {
        // Whole Year eg "2018"
        if (preg_match('/^[12][0-9]{3}$/', $period, $matches)) {
            $start = Carbon::create($matches[0], 1, 1, 0, 0, 0)->startOfYear();
            return new DatePeriod($start, new \DateInterval('P1D'), $start->copy()->endOfYear());
        }
        // Year + month eg. "2018-05"
        if (preg_match('/^([12][0-9]{3})-([01][0-9])$/', $period, $matches)) {
            $start = Carbon::create($matches[1], intval($matches[2]), 1, 0, 0, 0)->startOfMonth();
            return new DatePeriod($start, new \DateInterval('P1D'), $start->copy()->endOfMonth());
        }
        // Year + month + day eg. "2019-10-23"
        if (preg_match('/^([12][0-9]{3})-([01][0-9])-([0123][0-9])$/', $period, $matches)) {
            $start = Carbon::create($matches[1], intval($matches[2]), intval($matches[3]), 0, 0, 0)->startOfDay();
            return new DatePeriod($start, new \DateInterval('P1D'), $start->copy()->endOfDay());
        }
        // Start date - End date eg. "2019-10-21-2019-10-23"
        if (preg_match('/^([12][0-9]{3})-([01][0-9])-([01][0-9])-([12][0-9]{3})-([01][0-9])-([01][0-9])$/', $period, $matches)) {
            $start = Carbon::create($matches[1], intval($matches[2]), intval($matches[3]), 0, 0, 0)->startOfDay();
            $end = Carbon::create($matches[4], intval($matches[5]), intval($matches[6]), 0, 0, 0)->endOfDay();
            return new DatePeriod($start, new \DateInterval('P1D'), $end);
        }

        // Default fallback, Bullshit, actually
        return new DatePeriod(new \DateTime(), new \DateInterval('P1D'), new \DateTime('tomorrow'));
    }
}
