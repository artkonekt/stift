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

use Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Stift\Contracts\Issue;
use Konekt\Stift\Contracts\Requests\CreateIssue;
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
            'projects' => ProjectProxy::forUser(Auth::user())
        ]);
    }

    public function store(CreateIssue $request)
    {
        try {
            IssueProxy::create($request->all());

            flash()->success(__('Issue has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.issue.index'));
    }

}