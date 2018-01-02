<?php
/**
 * Contains the ProjectController class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-07-27
 *
 */


namespace Konekt\Stift\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Customer\Models\CustomerProxy;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Contracts\Requests\CreateProject;
use Konekt\Stift\Contracts\Requests\UpdateProject;
use Konekt\Stift\Models\ProjectProxy;
use Konekt\User\Models\UserProxy;

class ProjectController extends BaseController
{
    public function index()
    {
        return view('stift::project.index', [
            'projects' => ProjectProxy::forUser(Auth::user())->get()
        ]);
    }

    public function create()
    {
        return view('stift::project.create', [
            'project'   => app(Project::class),
            'customers' => CustomerProxy::all(),
            'users'     => UserProxy::all()
        ]);
    }

    public function store(CreateProject $request)
    {
        try {
            $project = ProjectProxy::create($request->all());
            $project->users()->sync($request->get('users'));

            flash()->success(__('Project has been created'));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.project.index'));
    }

    public function show(Project $project)
    {
        return view('stift::project.show', compact('project'));
    }

    public function edit(Project $project)
    {
        return view('stift::project.edit', [
            'project'   => $project,
            'customers' => CustomerProxy::all(),
            'users'     => UserProxy::all()
        ]);
    }

    public function update(Project $project, UpdateProject $request)
    {
        try {
            $project->update($request->all());
            //dd($request->get('users'));
            $project->users()->sync($request->get('users'));

            flash()->success(__('Project :name has been updated', ['name' => $project->name]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            return redirect()->back();
        }

        return redirect(route('stift.project.index'));
    }

    public function destroy(Project $project)
    {
        try {
            $name = $project->name;
            $project->delete();

            flash()->warning(__('The :name project has been deleted', ['name' => $name]));

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
        }

        return redirect(route('stift.project.index'));
    }

}
