<?php
/**
 * Contains the Issue model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Konekt\AppShell\Models\User;
use Konekt\Enum\Eloquent\CastsEnums;
use Konekt\Stift\Contracts\Issue as IssueContract;
use Konekt\User\Models\UserProxy;

/**
 * @property Project    $project
 * @property IssueType  $issueType
 * @property Severity   $severity
 * @property User       $createdBy
 * @property User|null  $assignedTo
 * @property Collection $worklogs
 */
class Issue extends Model implements IssueContract
{
    use CastsEnums;

    protected $enums = [
        'status' => 'IssueStatusProxy@enumClass'
    ];

    protected $dates = ['created_at', 'updated_at', 'due_on'];

    protected $fillable = [
        'id',
        'project_id',
        'issue_type_id',
        'severity_id',
        'subject',
        'description',
        'status',
        'priority',
        'original_estimate',
        'due_on',
        'created_by',
        'assigned_to'
    ];

    /**
     * Relation for the associated project
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function project()
    {
        return $this->hasOne(ProjectProxy::modelClass(), 'id', 'project_id');
    }

    /**
     * Relation for the associated issueType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function issueType()
    {
        return $this->hasOne(IssueTypeProxy::modelClass(), 'id', 'issue_type_id');
    }

    /**
     * Relation for the associated severity
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function severity()
    {
        return $this->hasOne(SeverityProxy::modelClass(), 'id', 'severity_id');
    }

    /**
     * Relation for the user created the issue
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function createdBy()
    {
        return $this->hasOne(UserProxy::modelClass(), 'id', 'created_by');
    }

    /**
     * Relation for the user the issue is assigned to
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function assignedTo()
    {
        return $this->hasOne(UserProxy::modelClass(), 'id', 'assigned_to');
    }

    public function worklogs()
    {
        return $this->hasMany(WorklogProxy::modelClass());
    }

    public function getMarkdownDescriptionAsHtml(): string
    {
        return (new \Parsedown())->parse($this->description);
    }

    /**
     * Returns the total duration (seconds) of work logged (excluding running)
     */
    public function worklogsTotalDuration()
    {
        return $this->worklogs()->notRunning()->sum('duration');
    }

    public function scopeOpen(Builder $query)
    {
        return $query->whereIn('status', IssueStatusProxy::getOpenStatuses());
    }

    public function scopeSort($query)
    {
        return $query->orderBy('priority');
    }
    public function scopeSortReverse($query)
    {
        return $query->orderBy('priority', 'desc');
    }

    public function visibleFor(User $user)
    {
        return ProjectUserProxy::forUser($user)->get()->contains('project_id', $this->project_id);
    }

    public function scopeUserHasAccessTo($query, User $user)
    {
        return $query->whereIn('project_id', ProjectProxy::forUser($user)->get()->pluck('id'));
    }

    public function getFormValue(string $key)
    {
        if ($this->isEnumAttribute($key)) {
            return $this->{$key}->value();
        }

        return $this->{$key};
    }
}
