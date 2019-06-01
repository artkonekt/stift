<div class="form-group{{ $errors->has('subject') ? ' has-danger' : '' }}">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-info-outline"></i>
        </span>
        {{ Form::text('subject', null, [
            'class' => 'form-control form-control-lg' . ($errors->has('subject') ? ' is-invalid' : ''),
            'placeholder' => __('Subject')
            ])
        }}
    </div>
    @if ($errors->has('subject'))
        <div class="invalid-feedback">{{ $errors->first('subject') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
    @if($projects->count() > 1)
        {{ Form::select('project_id', $projects->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => __('Project')]) }}
    @elseif($projects->count() == 0)
        <div class="alert alert-warning">
            <strong>{{ __("There's no project in the system.") }}</strong>
            @can('create projects')
                <a href="{{ route('stift.project.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create project') }}
                </a>
            @else
                <i class="zmdi zmdi-mood-bad"></i> {{ __("Unfortunately you can't create one") }}
            @endcan
        </div>
    @else
        <?php $project = $projects->first(); ?>
        {{ Form::hidden('project_id', $project->id) }}
        <label class="text-muted">{{ __('Project') }}: {{ $project->name }}</label>
    @endif

    @if ($errors->has('project_id'))
        <div class="form-control-feedback">{{ $errors->first('project_id') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
    @if($issue->project)
        @if($issue->project->users->count() > 1)
            {{ Form::select('assigned_to', $issue->project->users->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => __('Unassigned')]) }}
        @elseif($issue->project->users->count() == 0)
            <div class="alert alert-warning">
                <strong>{{ __("The project is not enabled for any user.") }}</strong>
                @can('edit projects')
                    <a href="{{ route('stift.project.edit', $issue->project) }}" class="btn btn-sm btn-outline-success float-right">
                        {{ __('Edit project') }}
                    </a>
                @else
                    <i class="zmdi zmdi-mood-bad"></i> {{ __("Unfortunately you have no permission to fix the situation") }}
                @endcan
            </div>
        @else
            <?php $user = $issue->project->users->first(); ?>
            {{ Form::hidden('assigned_to', $user->id) }}
            <label class="text-muted">{{ __('Assignee') }}: {{ $user->name }}</label>
        @endif
    @else
        {{ Form::select('assigned_to', $allUsers->pluck('name', 'id'), null, ['class' => 'form-control', 'placeholder' => __('Unassigned')]) }}
    @endif

    @if ($errors->has('assigned_to'))
        <div class="form-control-feedback">{{ $errors->first('assigned_to') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('description') ? ' has-danger' : '' }}">

    <label class="form-control-label">{{ __('Description') }}</label>
    {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Type issue details')]) }}

    @if ($errors->has('description'))
        <div class="form-control-feedback">{{ $errors->first('description') }}</div>
    @endif
</div>

<hr>

<div class="form-group row">
    <label class="form-control-label col-md-2">{{ __('Priority') }}</label>
    <div class="col-md-10">

        {{ Form::number('priority', null, [
                'class' => 'form-control form-control-sm' . ($errors->has('priority') ? ' is-invalid' : ''),
                'placeholder' => __('Value 1-99 or leave empty')
            ])
        }}

        @if ($errors->has('priority'))
            <div class="invalid-feedback">{{ $errors->first('priority') }}</div>
        @endif
    </div>
</div>


<hr>

<div class="form-group row">
    <label class="form-control-label col-md-2">{{ __('Status') }}</label>
    <div class="col-md-10">
        @foreach($statuses as $status => $statusLabel)
            <label class="radio-inline" for="status_{{ $status }}">
                {{ Form::radio('status', $status, ($issue->status->value() == $status), ['id' => "status_$status"]) }}
                {{ $statusLabel }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('status'))
            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
        @endif
    </div>
</div>

<div class="form-group row">

    <label class="form-control-label col-md-2">{{ __('Type') }}</label>
    <div class="col-md-10">
        @foreach($issueTypes as $type)
            <label class="radio-inline" for="type_{{ $type->id }}">
                {{ Form::radio('issue_type_id', $type->id, ($issue->type && $issue->type->id == $type->id), ['id' => "type_{$type->id}"]) }}
                {{ $type->name }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('issue_type_id'))
            <div class="invalid-feedback">{{ $errors->first('issue_type_id') }}</div>
        @endif
    </div>

</div>

<div class="form-group row">
    <label class="form-control-label col-md-2">{{ __('Severity') }}</label>
    <div class="col-md-10">
        @foreach($severities as $severity)
            <label class="radio-inline" for="severity_{{ $severity->id }}">
                {{ Form::radio('severity_id', $severity->id, ($issue->severity && $issue->severity->id == $severity->id), ['id' => "{$severity}_{$severity->id}"]) }}
                {{ $severity->name }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('severity_id'))
            <div class="invalid-feedback">{{ $errors->first('severity_id') }}</div>
        @endif
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{--<div class="form-group row {{ $errors->has('permissions') ? ' has-danger' : '' }}">--}}

    {{--@foreach($permissions as $permission)--}}
        {{--<div class="col-6 col-sm-2 @unless(($loop->index + 5) % 5)offset-sm-1 @endunless">--}}
            {{--{{ $permission->name }}--}}
            {{--<label class="switch switch-icon switch-pill switch-primary">--}}
                {{--{{ Form::checkbox("permissions[{$permission->name}]", 1, $role->hasPermissionTo($permission), ['class' => 'switch-input']) }}--}}
                {{--<span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>--}}
                {{--<span class="switch-handle"></span>--}}
            {{--</label>--}}
        {{--</div>--}}
    {{--@endforeach--}}

    {{--@if ($errors->has('permissions'))--}}
        {{--<div class="form-control-feedback">{{ $errors->first('permissions') }}</div>--}}
    {{--@endif--}}

{{--</div>--}}
