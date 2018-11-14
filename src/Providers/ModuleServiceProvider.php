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
use Konekt\Stift\Helpers\DurationHumanizer;
use Konekt\Stift\Http\Requests\CreateIssue;
use Konekt\Stift\Http\Requests\CreateProject;
use Konekt\Stift\Http\Requests\CreateWorklog;
use Konekt\Stift\Http\Requests\ListIssues;
use Konekt\Stift\Http\Requests\ListWorklogs;
use Konekt\Stift\Http\Requests\UpdateIssue;
use Konekt\Stift\Http\Requests\UpdateProject;
use Konekt\Stift\Http\Requests\UpdateWorklog;
use Konekt\Stift\Models\Issue;
use Konekt\Stift\Models\IssueStatus;
use Konekt\Stift\Models\IssueStatusProxy;
use Konekt\Stift\Models\IssueType;
use Konekt\Stift\Models\PredefinedPeriod;
use Konekt\Stift\Models\Project;
use Konekt\Stift\Models\ProjectUser;
use Konekt\Stift\Models\Severity;
use Konekt\Stift\Models\Worklog;
use Konekt\Stift\Models\WorklogState;
use Konekt\Stift\Models\WorklogStateProxy;
use Menu;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    protected $models = [
        Project::class,
        ProjectUser::class,
        IssueType::class,
        Severity::class,
        Issue::class,
        Worklog::class
    ];

    protected $requests = [
        CreateProject::class,
        UpdateProject::class,
        CreateIssue::class,
        UpdateIssue::class,
        CreateWorklog::class,
        UpdateWorklog::class,
        ListWorklogs::class,
        ListIssues::class
    ];

    protected $enums = [
        WorklogState::class,
        PredefinedPeriod::class,
        IssueStatus::class
    ];

    public function register()
    {
        parent::register();

        $this->app->singleton('stift.duration_humanizer', function () {
            return new DurationHumanizer();
        });
    }

    public function boot()
    {
        parent::boot();

        $this->registerEnumIcons();

        if ($menu = Menu::get('appshell')) {
            $menu->addItem('stift', __('Stift'));
            $menu->addItem('projects', __('Projects'), ['route' => 'stift.project.index'])
                ->data('icon', 'folder-star')
                ->allowIfUserCan('list projects');

            $menu->addItem('issues', __('Issues'), ['route' => ['stift.issue.index', 'status=open_issues']])
                ->data('icon', 'check-circle-u')
                ->allowIfUserCan('list issues');
            $menu->addItem('time_reports', __('Time Reports'), ['route' => 'stift.worklog.index'])
                ->data('icon', 'collection-text')
                ->allowIfUserCan('list worklogs');
        }
    }

    private function registerEnumIcons()
    {
        $this->app['appshell.icon']->registerEnumIcons(
            WorklogStateProxy::enumClass(),
            [
                WorklogState::RUNNING  => 'spinner',
                WorklogState::PAUSED   => 'pause',
                WorklogState::FINISHED => 'calendar-check',
                WorklogState::APPROVED => 'check-all',
                WorklogState::REJECTED => 'flash',
                WorklogState::BILLED   => 'money'
            ]
        );

        $this->app['appshell.icon']->registerEnumIcons(
            IssueStatusProxy::enumClass(),
            [
                IssueStatus::TODO        => 'circle-o',
                IssueStatus::IN_PROGRESS => 'spinner',
                IssueStatus::DONE        => 'check-circle-u'
            ]
        );
    }
}
