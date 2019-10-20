<?php
/**
 * Contains the Label class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;
use Konekt\Stift\Contracts\Label as LabelContract;
use Konekt\User\Contracts\User;

class Label extends Model implements LabelContract
{
    use Sluggable;

    protected $fillable = [
        'title',
        'color',
        'slug',
        'project_id',
    ];

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    public function colorAsHex(): string
    {
        return Str::startsWith($this->color, '#') ? $this->color : app('appshell.theme')->semanticColorToHex($this->color);
    }

    public function project(): HasOne
    {
        return $this->hasOne(ProjectProxy::modelClass(), 'id', 'project_id');
    }

    public function visibleFor(User $user): bool
    {
        return ProjectUserProxy::forUser($user)->get()->contains('project_id', $this->project_id);
    }

    public function scopeForUser($query, User $user)
    {
        return $query->whereIn('project_id',
            ProjectUserProxy::forUser($user)->get()->pluck('project_id')
        );
    }

    public function issues()
    {
        return $this->hasMany(IssueProxy::modelClass());
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

}
