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
            'period'     => ['sometimes', function($attribute, $value, $fail) {
                if (null === $this->getPeriod($value)) {
                    $fail("Invalid period `$value`. Examples: 2018, 2019-10, 2019-10-11, 2019-01-2019-14, 2019-02-01-2019-03-15");
                }
            }]
        ];
    }

    /**
     * @inheritDoc
     */
    public function authorize()
    {
        return true;
    }

    public function getPeriod(string $period = null): ?DatePeriod
    {
        $period = $period ?: $this->get('period');

        if (is_null($period) || PredefinedPeriodProxy::has($period)) {
            return PredefinedPeriodProxy::create($period)->getDatePeriod();
        }

        return $this->parsePeriod($period);
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

    public function parsePeriod(string $period): ?DatePeriod
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

        // Start date - End date eg. "2019-09-2019-12"
        if (preg_match('/^([12][0-9]{3})-([01][0-9])-([12][0-9]{3})-([01][0-9])$/', $period, $matches)) {
            $start = Carbon::create($matches[1], intval($matches[2]), 1, 0, 0, 0)->startOfMonth();
            $end = Carbon::create($matches[3], intval($matches[4]), 1, 0, 0, 0)->endOfMonth();
            return new DatePeriod($start, new \DateInterval('P1D'), $end);
        }

        // Start date - End date eg. "2019-10-21-2019-10-23"
        if (preg_match('/^([12][0-9]{3})-([01][0-9])-([0123][0-9])-([12][0-9]{3})-([01][0-9])-([0123][0-9])$/', $period, $matches)) {
            $start = Carbon::create($matches[1], intval($matches[2]), intval($matches[3]), 0, 0, 0)->startOfDay();
            $end = Carbon::create($matches[4], intval($matches[5]), intval($matches[6]), 0, 0, 0)->endOfDay();
            return new DatePeriod($start, new \DateInterval('P1D'), $end);
        }

        return null;
    }
}
