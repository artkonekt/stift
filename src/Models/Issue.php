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


use Illuminate\Database\Eloquent\Model;
use Konekt\Stift\Contracts\Issue as IssueContract;
use Konekt\User\Models\UserProxy;

/**
 * @property \Konekt\Stift\Contracts\Project $project
 */
class Issue extends Model implements IssueContract
{
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
}