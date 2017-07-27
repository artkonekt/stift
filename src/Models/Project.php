<?php
/**
 * Contains the Project model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Konekt\Client\Models\ClientProxy;
use Konekt\Stift\Contracts\Project as ProjectContract;
use Konekt\User\Contracts\User;

class Project extends Model implements ProjectContract
{
    /**
     * @var bool Project id's are non-numeric, they're the project slug
     */
    public $incrementing = false;

    protected $fillable = ['id', 'name', 'client_id', 'is_active'];

    /**
     * Every project has several issue types enabled for it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function issueTypes()
    {
        return $this->belongsToMany(
            IssueTypeProxy::modelClass(),
            'project_issue_types'
        );
    }

    /**
     * Every project has several severities enabled for it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function severities()
    {
        return $this->belongsToMany(
            SeverityProxy::modelClass(),
            'project_severities'
        );
    }

    public function client()
    {
        return $this->hasOne(ClientProxy::modelClass(), 'id', 'client_id');
    }

    /**
     * @inheritdoc
     */
    public function visibleFor(User $user)
    {
        return ProjectUserProxy::forUser($user)->get()->contains('project_id', $this->id);
    }


    public function scopeForUser($query, User $user)
    {
        return $query->whereIn('id',
            ProjectUserProxy::forUser($user)->get()->pluck('project_id')
        );
    }


}