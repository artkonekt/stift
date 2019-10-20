<?php
/**
 * Contains the IssueController class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-17
 *
 */

namespace Konekt\Stift\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Gears\Facades\Settings;
use Konekt\Stift\Contracts\Issue;
use Konekt\Stift\Contracts\Requests\CreateIssue;
use Konekt\Stift\Contracts\Requests\ListIssues;
use Konekt\Stift\Contracts\Requests\UpdateIssue;
use Konekt\Stift\Contracts\Requests\UpdateIssueLabels;
use Konekt\Stift\Models\IssueProxy;
use Konekt\Stift\Models\IssueStatusProxy;
use Konekt\Stift\Models\IssueTypeProxy;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\Stift\Models\SeverityProxy;
use Konekt\User\Models\UserProxy;

class IssueController extends BaseController
{
    public function index(ListIssues $request)
    {
        try {
            $filteredProjects = false;

            $issues = IssueProxy::query();

            if (!empty($projectIds = $request->getProjects())) {
                $issues->whereIn('project_id', $projectIds);
                $filteredProjects = ProjectProxy::whereIn('id', $projectIds)->get();
            }

            if (!empty($statuses = $request->getStatuses())) {
                $issues->whereIn('status', $statuses);
            }
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return view('stift::issue.index', [
            'issues'           => $issues->userHasAccessTo(Auth::user())->sort()->get(),
            'projects'         => ProjectProxy::forUser(Auth::user())->get()->sortBy('name')->pluck('name', 'id'),
            'filteredProjects' => $filteredProjects,
            'statuses'         => [
                'open_issues'         => __('Open Issues'),
                __('Specific Status') => IssueStatusProxy::choices()
            ]
        ]);
    }

    public function create(Request $request)
    {
        $issue             = app(Issue::class);
        $issue->project_id = $request->get('forProject');

        return view('stift::issue.create', [
            'issue'      => $issue,
            'projects'   => ProjectProxy::forUser(Auth::user())->get()->sortBy('name'),
            'statuses'   => IssueStatusProxy::choices(),
            'issueTypes' => IssueTypeProxy::all(),
            'severities' => SeverityProxy::all(),
            'allUsers'   => UserProxy::active()->get()
        ]);
    }

    public function store(CreateIssue $request)
    {
        $data               = $request->all();
        $data['created_by'] = Auth::user()->id;
        $data['priority']   = empty($data['priority']) ? Settings::get('stift.issues.default_priority') : $data['priority'];

        try {
            $issue = IssueProxy::create($data);

            flash()->success(__('Issue has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('stift.project.show', $issue->project));
    }

    public function show(Issue $issue)
    {
        if (!$issue->visibleFor(Auth::user())) {
            abort(403);
        }

        return view('stift::issue.show', ['issue' => $issue]);
    }

    public function edit(Issue $issue)
    {
        if (!$issue->visibleFor(Auth::user())) {
            abort(403);
        }

        return view('stift::issue.edit', [
            'issue'      => $issue,
            'projects'   => ProjectProxy::forUser(Auth::user())->get()->sortBy('name'),
            'statuses'   => IssueStatusProxy::choices(),
            'issueTypes' => IssueTypeProxy::all(),
            'severities' => SeverityProxy::all()
        ]);
    }

    public function update(Issue $issue, UpdateIssue $request)
    {
        if (!$issue->visibleFor(Auth::user())) {
            abort(403);
        }

        try {
            $issue->update($request->all());

            flash()->success(__('Issue has been updated'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('stift.issue.show', $issue));
    }

    public function destroy(Issue $issue)
    {
        if (!$issue->visibleFor(Auth::user())) {
            abort(403);
        }

        try {
            $subject = $issue->subject;
            $issue->delete();

            flash()->warning(__('Issue `:subject` has been deleted', ['subject' => $subject]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }

        return redirect(route('stift.issue.index', ['status' => 'open_issues']));
    }

    public function labels(UpdateIssueLabels $request, Issue $issue)
    {
        $issue->labels()->sync($request->getLabelIds());

        return redirect(route('stift.issue.show', $issue));
    }
}
