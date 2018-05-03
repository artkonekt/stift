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

use Illuminate\Database\Eloquent\Model;
use Konekt\Enum\Eloquent\CastsEnums;
use Konekt\Stift\Contracts\Worklog as WorklogContract;
use Konekt\User\Models\UserProxy;

class Worklog extends Model implements WorklogContract
{
    use CastsEnums;

    protected $dates = ['created_at', 'updated_at', 'started_at', 'finished_at'];

    protected $guarded = ['id'];

    protected $enums = [
        'state' => 'WorklogStateProxy@enumClass'
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
}
