<?php

use Konekt\Stift\Contracts\Issue;
use Konekt\Stift\Models\IssueProxy;

function duration_secs_to_human_readable(int $seconds, $withSeconds = false)
{
    return app('stift.duration_humanizer')->secondsToHumanReadable($seconds, $withSeconds);
}

function duration_in_hours(int $seconds)
{
    return round($seconds / 3600, 2);
}

function show_duration_in_hours(int $seconds)
{
    return duration_in_hours($seconds) . ' ' . __('h');
}

function stift_open_issues($include)
{
    /** @var \Illuminate\Database\Eloquent\Collection $openIssues */
    $openIssues = IssueProxy::open()->get();

    if ($include instanceof Issue) {
        if (!$openIssues->contains($include->id)) {
            $openIssues->put($include->id, $include);
        }
    }

    return $openIssues;
}
