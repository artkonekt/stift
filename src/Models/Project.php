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


use Illuminate\Database\Eloquent\Model;
use Konekt\Stift\Contracts\Project as ProjectContract;

class Project extends Model implements ProjectContract
{
    /**
     * @var bool Project id's are non-numeric, they're the project slug
     */
    public $incrementing = false;

    protected $fillable = ['id', 'name', 'client_id'];

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

}