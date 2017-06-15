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

class Issue extends Model implements IssueContract
{
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

}