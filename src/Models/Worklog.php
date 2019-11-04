<?php
/**
 * Contains the Worklog model class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-05-03
 */

namespace Konekt\Stift\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Contracts\Worklog as WorklogContract;
use Konekt\User\Contracts\User;
use Konekt\User\Models\UserProxy;

/**
 * @property integer $id
 * @property string  $description
 * @property integer $duration
 * @property Carbon  $created_at
 * @property Carbon  $updated_at
 * @property Carbon  $started_at
 * @property Carbon  $finished_at
 * @property WorklogState $state
 * @property User    $user
 * @property Issue   $issue
 */
class Worklog extends Model implements WorklogContract
{
    use CastsEnums;

    protected $dates = ['created_at', 'updated_at', 'started_at', 'finished_at'];

    protected $guarded = ['id'];

    protected $enums = [
        'state' => 'WorklogStateProxy@enumClass'
    ];

    protected $casts = [
        'is_billable' => 'boolean',
    ];

    /**
     * Relation for the associated issue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function issue()
    {
        return $this->hasOne(IssueProxy::modelClass(), 'id', 'issue_id');
    }

    /**
     * Relation for the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(UserProxy::modelClass(), 'id', 'user_id');
    }

    public function isRunning()
    {
        return $this->state->isRunning();
    }

    /**
     * Returns the number of seconds since the worklog is running.
     * Returns NULL for non running worklogs
     *
     * @return int|null
     */
    public function runningDuration()
    {
        if ($this->isRunning()) {
            return $this->started_at->diffInSeconds();
        }

        return null;
    }

    public function scopeRunning($q)
    {
        return $q->where('state', 'running');
    }

    public function scopeNotRunning($q)
    {
        return $q->where('state', '<>', 'running');
    }

    public function scopeRecent($q, $numEntries = 10)
    {
        return $q->orderBy('started_at', 'desc')->take($numEntries);
    }

    public function scopeOfUser($q, User $user)
    {
        return $q->where('user_id', $user->id);
    }

    public function scopeOfUsers($q, array $users)
    {
        $userIds = [];
        foreach ($users as $user) {
            $userIds[] = $user instanceof User ? $user->id : $user;
        }

        return $q->whereIn('user_id', $userIds);
    }

    public function scopeOfProject($q, Project $project)
    {
        return $q->whereIn('issue_id', $project->issues->pluck('id'));
    }

    public function scopeOfProjects($q, array $projects)
    {
        $issueIds = collect();
        foreach ($projects as $project) {
            $issueIds = $issueIds->merge($project->issues->pluck('id'));
        }

        return $q->whereIn('issue_id', $issueIds);
    }

    public function scopeUserHasAccessTo($query, User $user)
    {
        return $query
            ->leftJoin('issues', 'worklogs.issue_id', '=', 'issues.id')
            ->select('worklogs.*', 'issues.project_id')
            ->whereIn(
                'issues.project_id',
                ProjectProxy::forUser($user)->get()->pluck('id')
            );
    }

    public function scopeAfter($q, \DateTime $date)
    {
        return $q->where('started_at', '>=', $date->format('Y-m-d H:i:s'));
    }

    public function scopeBefore($q, \DateTime $date)
    {
        return $q->where('started_at', '<=', $date->format('Y-m-d H:i:s'));
    }

    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    public function scopeNonBillable($query)
    {
        return $query->where('is_billable', false);
    }
}
