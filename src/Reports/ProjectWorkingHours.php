<?php
/**
 * Contains the ProjectWorkingHours class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-23
 *
 */

namespace Konekt\Stift\Reports;

use DatePeriod;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Models\WorklogProxy;

class ProjectWorkingHours extends BaseReport
{
    /** @var Project */
    private $project;

    /** @var int|null */
    private $duration;

    public static function create(string $period, Project $project)
    {
        return new static($project, static::getPeriodFromString($period));
    }

    public function __construct(Project $project, DatePeriod $period)
    {
        parent::__construct($period);

        $this->project = $project;
    }

    public function getProject(): Project
    {
        return $this->project;
    }

    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    public function getWorkingHours()
    {
        if (null === $this->duration) {
            $this->duration = WorklogProxy::ofProject($this->project)
                                          ->after($this->period->start)
                                          ->before($this->period->end)
                                          ->notRunning()
                                          ->sum('duration');
        }

        return round($this->duration / 3600, 2);
    }

}
