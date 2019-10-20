<?php
/**
 * Contains the IssueLabel class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Konekt\Stift\Contracts\IssueLabel as IssueLabelContract;
use Konekt\User\Contracts\User;
use Konekt\User\Models\UserProxy;

class IssueLabel extends Model implements IssueLabelContract
{
    protected $fillable = ['issue_id', 'label_id'];

    public function label(): BelongsTo
    {
        return $this->belongsTo(LabelProxy::modelClass());
    }

    public function issue(): BelongsTo
    {
        return $this->belongsTo(IssueProxy::modelClass());
    }

//    public function scopeForUser($query, User $user)
//    {
//        return $query->where('user_id', $user->id);
//    }

    /**
     * Returns all entries for specified projects
     * @param $query
     * @param Collection $projects
     *
     * @return mixed
     */
//    public function scopeByProjects($query, $projects)
//    {
//        return $query->whereIn('project_id', $projects);
//    }
}
