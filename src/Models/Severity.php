<?php
/**
 * Contains the Severity model class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Witser\Models;


use Illuminate\Database\Eloquent\Model;
use Konekt\Witser\Contracts\Severity as SeverityContract;

class Severity extends Model implements SeverityContract
{
    /**
     * @var bool Severity id's are non-numeric, they're the slug
     */
    public $incrementing = false;

    protected $fillable = ['id', 'name', 'weight'];

    /**
     * Returns the projects this severity has been enabled for
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(
            ProjectProxy::modelClass(),
            'project_severities'
        );
    }

}