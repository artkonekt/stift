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
use Konekt\Stift\Contracts\PredefinedPeriod;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\WorklogProxy;

class TimeReport extends BaseReport
{
    /** @var Project[] */
    protected $projects = [];

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

    public function getWorklogs()
    {
        return $this->getQuery()->get();
    }

    public function getDuration()
    {
        return $this->getQuery()->sum('duration');
    }

    protected function getQuery()
    {
        $query = WorklogProxy::join('issues', 'worklogs.issue_id', '=', 'issues.id')
                    ->notRunning()
                    ->after($this->period->start)
                    ->before($this->period->end)
                    ->orderBy('issues.project_id')
                    ->orderBy('issue_id')
                    ->orderBy('started_at')

                   ;
        if (!empty($this->projects)) {
            $query->ofProjects($this->projects);
        }

        return $query;
    }

}
