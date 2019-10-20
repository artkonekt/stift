<?php
/**
 * Contains the LabelController class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-10-20
 *
 */

namespace Konekt\Stift\Http\Controllers;

use Konekt\AppShell\Contracts\Theme;
use Konekt\AppShell\Http\Controllers\BaseController;
use Konekt\Stift\Contracts\Label;
use Konekt\Stift\Contracts\Project;
use Konekt\Stift\Contracts\Requests\CreateLabel;
use Konekt\Stift\Contracts\Requests\UpdateLabel;
use Konekt\Stift\Models\LabelProxy;

class LabelController extends BaseController
{
    /** @var Theme */
    protected $theme;

    public function __construct()
    {
        $this->theme = app('appshell.theme');
    }

    public function create(Project $project)
    {
        $label             = app(Label::class);
        $label->project_id = $project->id;
        $label->color      = $this->theme->semanticColorToHex('primary');

        return view('stift::label.create', [
            'project'      => $project,
            'label'        => $label,
            'presetColors' => $this->getPresetColors()
        ]);
    }

    public function store(Project $project, CreateLabel $request)
    {
        try {
            $label = LabelProxy::create(
                array_merge(
                    $request->all(),
                    ['project_id' => $project->id]
                )
            );

            flash()->success(__('Label :title has been created', [
                'title' => $label->title
            ]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('stift.project.show', $project));
    }

    public function edit(Project $project, Label $label)
    {
        return view('stift::label.edit', [
            'project'      => $project,
            'label'        => $label,
            'presetColors' => $this->getPresetColors()
        ]);
    }

    public function update(Project $project, Label $label, UpdateLabel $request)
    {
        try {
            $label->update($request->all());

            flash()->success(__(':title has been updated', ['title' => $label->title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back()->withInput();
        }

        return redirect(route('stift.project.show', $project));
    }

    public function destroy(Project $project, Label $label)
    {
        try {
            $title = $label->title;
            $label->delete();

            flash()->warning(__(':title has been deleted', ['title' => $title]));
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));

            return redirect()->back();
        }

        return redirect(route('stift.project.show', $project));
    }

    private function getPresetColors(): array
    {
        return collect(['primary', 'success', 'info', 'secondary', 'warning', 'danger'])
            ->mapWithKeys(function ($elem) {
                return [$elem => $this->theme->semanticColorToHex($elem)];
            })
            ->all();
    }
}
