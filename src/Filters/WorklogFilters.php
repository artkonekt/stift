<?php
/**
 * Contains the WorklogFilters class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-05-05
 *
 */

namespace Konekt\Stift\Filters;

use Illuminate\Contracts\Auth\Authenticatable;
use Konekt\Stift\Contracts\PredefinedPeriod;
use Konekt\Stift\Contracts\Requests\ListWorklogs;

class WorklogFilters
{
    /** @var Authenticatable */
    private $user;

    /** @var Users */
    private $users;

    /** @var Period */
    private $period;

    /** @var Projects */
    private $projects;

    /** @var Billable */
    protected $billable;

    public static function createFromRequest(ListWorklogs $request, Authenticatable $user)
    {
        return new static(
            $user,
            $request->getUsers(),
            $request->getPeriod(),
            $request->getProjects(),
            $request->getBillable()
        );
    }

    public function __construct(
        Authenticatable $user,
        array $users = [],
        ?PredefinedPeriod $period = null,
        array $projects = [],
        ?bool $billable = null
    )
    {
        $this->user     = $user;
        $this->users    = new Users($users);
        $this->period   = new Period($period);
        $this->projects = new Projects($user, $projects);
        $this->billable = new Billable($billable);
    }

    public function value(string $filter)
    {
        return $this->{$filter}->getValue();
    }

    public function options(string $filter)
    {
        return $this->{$filter}->getOptions();
    }

    public function isDefined(string $filter): bool
    {
        return $this->{$filter}->isDefined();
    }

    public function isNotDefined(string $filter): bool
    {
        return $this->{$filter}->isNotDefined();
    }
}
