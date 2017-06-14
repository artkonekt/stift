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


namespace Konekt\Witser\Providers;


use Konekt\Concord\BaseBoxServiceProvider;
use Konekt\Witser\Models\Issue;
use Konekt\Witser\Models\IssueType;
use Konekt\Witser\Models\Project;
use Konekt\Witser\Models\Severity;

class ModuleServiceProvider extends BaseBoxServiceProvider
{
    protected $models = [
        Project::class,
        IssueType::class,
        Severity::class,
        Issue::class
    ];

}