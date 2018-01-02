<?php
/**
 * Contains the IssueController.php class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-17
 *
 */


namespace Konekt\Stift\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Stift\Contracts\Issue;
use Konekt\Stift\Contracts\Requests\CreateIssue;
use Konekt\Stift\Contracts\Requests\UpdateIssue;
use Konekt\Stift\Models\IssueProxy;
use Konekt\Stift\Models\ProjectProxy;

class IssueController extends BaseController
{
    public function index()
    {
        return view('stift::issue.index', [
            'issues' => IssueProxy::all()
        ]);
    }

    public function create()
    {
        return view('stift::issue.create', [
            'issue'    => app(Issue::class),
            'projects' => ProjectProxy::forUser(Auth::user())->get(),
            'statuses' => ['todo', 'in-progress', 'done']
        ]);
    }

    public function store(CreateIssue $request)
    {
        $data = $request->all();
        $data['created_by'] = Auth::user()->id;

        try {
            IssueProxy::create($data);

            flash()->success(__('Issue has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.issue.index'));
    }

    public function show(Issue $issue)
    {
        return view('stift::issue.show', ['issue' => $issue]);
    }

    public function edit(Issue $issue)
    {
        return view('stift::issue.edit', [
            'issue'    => $issue,
            'projects' => ProjectProxy::forUser(Auth::user())->get(),
            'statuses' => ['todo', 'in-progress', 'done']
        ]);
    }

    public function update(Issue $issue, UpdateIssue $request)
    {
        try {
            $issue->update($request->all());

            flash()->success(__('Issue has been updated'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.issue.index'));
    }

    public function destroy(Issue $issue)
    {
        try {
            $subject = $issue->subject;
            $issue->delete();

            flash()->warning(__('Issue `:subject` has been deleted', ['subject' => $subject]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }

        return redirect(route('stift.issue.index'));
    }

}
