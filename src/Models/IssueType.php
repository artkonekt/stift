<?php
/**
 * Contains the IssueType model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Models;


use Illuminate\Database\Eloquent\Model;
use Konekt\Stift\Contracts\IssueType as IssueTypeContract;

class IssueType extends Model implements IssueTypeContract
{
    /**
     * @var bool Issue Type id's are non-numeric, they're the type slug
     */
    public $incrementing = false;

    protected $fillable = ['id', 'name'];

    /**
     * Returns the projects this issue type has been enabled for
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(
            ProjectProxy::modelClass(),
            'project_issue_types'
        );
    }

}