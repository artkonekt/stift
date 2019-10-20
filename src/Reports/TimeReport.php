<?php
/**
 * Contains the TimeReport class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-31
 *
 */

namespace Konekt\Stift\Reports;

use DatePeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\WorklogProxy;
use Konekt\User\Contracts\User;

class TimeReport extends BaseReport
{
    /** @var Project[] */
    protected $projects = [];

    /** @var array */
    private $projectsFilter;

    /** @var array User[] */
    protected $usersFilter = [];

    /** @var bool|null */
    protected $billable;

    /** @var Collection|null */
    protected $worklogs;

    /** @var array|null */
    protected $users;

    /** @var int|null */
    protected $duration;

    /** @var array|null */
    protected $projectTotals;

    /** @var array|null */
    protected $userTotals;

    /** @var int|null */
    protected $billableTotal;

    /** @var int|null */
    protected $nonBillableTotal;

    public static function create(DatePeriod $period, array $projects, array $users = [], bool $billable = null)
    {
        return new static($period, $projects, $users, $billable);
    }

    public function __construct(DatePeriod $period, array $projects, array $users, bool $billable = null)
    {
        parent::__construct($period);

        $this->usersFilter    = $users;
        $this->projectsFilter = $projects;
        $this->billable       = $billable;

        foreach ($projects as $project) {
            if ($project instanceof Project) {
                $this->projects[] = $project;
            } else {
                if (!$model = ProjectProxy::find($project)) {
                    throw new \Exception(sprintf('%s is not a valid project', $project));
                }
                $this->projects[] = $model;
            }
        }
    }

    public function getPeriod()
    {
        return clone $this->period;
    }

    public function getWorklogs(): Collection
    {
        if (null === $this->worklogs) {
            $this->worklogs = $this->getQuery()->get();
        }

        return $this->worklogs;
    }

    public function getDuration(): int
    {
        if (null === $this->duration) {
            $this->duration = $this->getQuery()->sum('duration');
        }

        return $this->duration;
    }

    public function getProjects(): array
    {
        return $this->projects;
    }

    public function getProjectTotals()
    {
        if (null === $this->projectTotals) {
            $query                     = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->projectTotals       = $query
                ->select([])
                ->selectRaw('issues.project_id, SUM(duration) as total')
                ->groupBy('issues.project_id')
                ->pluck('total', 'issues.project_id');
        }

        return $this->projectTotals;
    }

    public function getUsers(): array
    {
        if (null === $this->users) {
            $query                     = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->users               = $query
                ->select(['user_id'])
                ->groupBy('user_id')
                ->get()
                ->map(function ($result) {
                    return $result->user;
                })
                ->keyBy('id')
                ->all()
            ;
        }

        return $this->users;
    }

    public function getUserTotals()
    {
        if (null === $this->userTotals) {
            $query                     = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->userTotals          = $query
                ->select([])
                ->selectRaw('user_id, SUM(duration) as total')
                ->groupBy('user_id')
                ->pluck('total', 'user_id');
        }

        return $this->userTotals;
    }

    public function userTotal(User $user): int
    {
        return $this->getUserTotals()[$user->id];
    }

    public function projectTotal(Project $project): int
    {
        return $this->getProjectTotals()[$project->id];
    }

    public function reportsBothBillableAndNonBillableHours(): bool
    {
        return is_null($this->billable);
    }

    public function billableTotal()
    {
        if (null === $this->billableTotal) {
            $query                     = $this->getQuery()->cloneWithoutBindings(['order']);
            //$query->getQuery()->orders = []; // Reset order by clauses
            $this->billableTotal       = $query->where('is_billable', true)->sum('duration');
        }

        return $this->billableTotal;
    }

    public function nonBillableTotal()
    {
        if (null === $this->nonBillableTotal) {
            $query                     = $this->getQuery()->cloneWithoutBindings(['order']);
            //$query->getQuery()->orders = []; // Reset order by clauses
            $this->nonBillableTotal       = $query->where('is_billable', false)->sum('duration');
        }

        return $this->nonBillableTotal;
    }

    protected function getQuery(): Builder
    {
        $query = WorklogProxy::leftJoin('issues', 'worklogs.issue_id', '=', 'issues.id')
                    ->select('worklogs.*')
                    ->notRunning()
                    ->after($this->period->start)
                    ->before($this->period->end)
                    ->orderBy('issues.project_id')
                    ->orderBy('issue_id')
                    ->orderBy('started_at');

        if (!empty($this->projectsFilter)) {
            $query->ofProjects($this->projects);
        }

        if (!empty($this->usersFilter)) {
            $query->ofUsers($this->usersFilter);
        }

        if (null !== $this->billable) {
            if ($this->billable) {
                $query->billable();
            } else {
                $query->nonBillable();
            }
        }

        return $query;
    }
}
