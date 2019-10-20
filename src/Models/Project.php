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

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Konekt\Customer\Models\CustomerProxy;
use Konekt\Stift\Contracts\Project as ProjectContract;
use Konekt\User\Contracts\User;
use Konekt\User\Models\UserProxy;

class Project extends Model implements ProjectContract
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'customer_id',
        'is_active',
        'is_billable',
        'pricing_model',
        'pricing_configuration',
    ];

    protected $casts = [
        'is_billable' => 'boolean',
    ];

    /**
     * Every project has several issue types enabled for it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function issueTypes()
    {
        return $this->belongsToMany(IssueTypeProxy::modelClass(), 'project_issue_types');
    }

    /**
     * Every project has several severities enabled for it
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function severities()
    {
        return $this->belongsToMany(SeverityProxy::modelClass(), 'project_severities');
    }

    /**
     * Users who have access to the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(UserProxy::modelClass(), 'project_users');
    }

    public function labels()
    {
        return $this->hasMany(LabelProxy::modelClass(), 'project_id', 'id');
    }

    /**
     * The customer the project belongs to
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function customer()
    {
        return $this->hasOne(CustomerProxy::modelClass(), 'id', 'customer_id');
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

    public function scopeActives($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactives($query)
    {
        return $query->where('is_active', false);
    }

    public function issues()
    {
        return $this->hasMany(IssueProxy::modelClass());
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
