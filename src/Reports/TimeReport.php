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
use Illuminate\Database\Eloquent\Collection;
use Konekt\Stift\Contracts\PredefinedPeriod;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\WorklogProxy;
use Konekt\User\Contracts\User;

class TimeReport extends BaseReport
{
    /** @var Project[] */
    protected $projects = [];

    /** @var Collection|null */
    protected $worklogs;

    /** @var array|null */
    protected $users;

    /** @var int|null */
    protected $duration;

    /** @var int|null */
    protected $projectTotals;

    /** @var int|null */
    protected $userTotals;

    public static function create(PredefinedPeriod $period, array $projects)
    {
        return new static($period->getDatePeriod(), $projects);
    }

    public function __construct(DatePeriod $period, array $projects)
    {
        parent::__construct($period);

        foreach ($projects as $project) {
            if ($project instanceof Project) {
                $this->projects[] = $project;
            } else {
                if (!$model = ProjectProxy::find($project)) {
                    throw new \Exception(sprintf('%s is not a valid project', $project));
                }
                $this->projects[] = $model;            }
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
            $query = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->projectTotals = $query
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
            $query = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->users = $query
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
            $query = $this->getQuery()->cloneWithoutBindings(['select', 'order']);
            $query->getQuery()->orders = []; // Reset order by clauses
            $this->userTotals = $query
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

    protected function getQuery()
    {
        $query = WorklogProxy::leftJoin('issues', 'worklogs.issue_id', '=', 'issues.id')
                    ->select('worklogs.*')
                    ->notRunning()
                    ->ofProjects($this->projects)
                    ->after($this->period->start)
                    ->before($this->period->end)
                    ->orderBy('issues.project_id')
                    ->orderBy('issue_id')
                    ->orderBy('started_at');

        return $query;
    }

}
