<?php
/**
 * Contains the ProjectUser model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */

namespace Konekt\Stift\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Konekt\Stift\Contracts\ProjectUser as ProjectUserContract;
use Konekt\User\Contracts\User;
use Konekt\User\Models\UserProxy;

class ProjectUser extends Model implements ProjectUserContract
{
    protected $fillable = ['project_id', 'user_id'];


    /**
     * Get the user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(UserProxy::modelClass());
    }

    /**
     * Get the project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(ProjectProxy::modelClass());
    }

    /**
     * Scope filter for the entries by user
     *
     * @param $query
     * @param User $user
     *
     * @return mixed
     */
    public function scopeForUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Returns all entries for specified projects
     * @param $query
     * @param Collection $projects
     *
     * @return mixed
     */
    public function scopeByProjects($query, $projects)
    {
        return $query->whereIn('project_id', $projects);
    }
}
