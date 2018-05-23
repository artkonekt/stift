<?php
/**
 * Contains the UserWorkingHours class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-21
 *
 */

namespace Konekt\Stift\Reports;

use DateInterval;
use DatePeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Konekt\Stift\Models\WorklogProxy;
use Konekt\User\Contracts\User;

class UserWorkingHours extends BaseReport
{
    /** @var User */
    private $user;

    /** @var int|null */
    private $duration;

    public static function create(string $period, $user = null)
    {
        return new static($user ?: Auth::user(), static::getPeriodFromString($period));
    }

    public function __construct(User $project, DatePeriod $period)
    {
        parent::__construct($period);

        $this->user = $project;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getPeriod(): DatePeriod
    {
        return $this->period;
    }

    public function getWorkingHours()
    {
        if (null === $this->duration) {
            $this->duration = WorklogProxy::ofUser($this->user)
                      ->after($this->period->start)
                      ->before($this->period->end)
                      ->notRunning()
                      ->sum('duration');
        }

        return round($this->duration / 3600, 2);
    }
}
