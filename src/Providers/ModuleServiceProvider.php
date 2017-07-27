<?php
/**
 * Contains the ModuleServiceProvider class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-06-14
 *
 */


namespace Konekt\Stift\Providers;


use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Stift\Http\Requests\CreateIssue;
use Konekt\Stift\Http\Requests\CreateProject;
use Konekt\Stift\Http\Requests\UpdateIssue;
use Konekt\Stift\Http\Requests\UpdateProject;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\ProjectUser;
use Konekt\Stift\Models\Severity;
use Menu;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    protected $models = [
        Project::class,
        ProjectUser::class,
        IssueType::class,
        Severity::class,
        Issue::class
    ];

    protected $requests = [
        CreateProject::class,
        UpdateProject::class,
        CreateIssue::class,
        UpdateIssue::class
    ];

    public function boot()
    {
        parent::boot();

        if ($menu = Menu::get('appshell')) {
            $menu->addItem('projects', __('Projects'), ['route' => 'stift.project.index'])->data('icon', 'folder-star');
            $menu->addItem('issues', __('Issues'), ['route' => 'stift.issue.index'])->data('icon', 'check-circle-u');
        }
    }


}