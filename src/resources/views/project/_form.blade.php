<div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
    <div class="input-group">
        <span class="input-group-addon">
            <i class="zmdi zmdi-info-outline"></i>
        </span>
        {{ Form::text('name', null, ['class' => 'form-control form-control-lg', 'placeholder' => __('Name of the project')]) }}
    </div>
    @if ($errors->has('name'))
        <div class="form-control-feedback">{{ $errors->first('name') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('id') ? ' has-danger' : '' }}">
    {{ Form::text('id', null, ['class' => 'form-control form-control-sm', 'placeholder' => __('Project key (also its URI)')]) }}
    @if ($errors->has('id'))
        <div class="form-control-feedback">{{ $errors->first('id') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
    @if($clients->count() > 1)
        {{ Form::select('client_id', $clients, null, ['class' => 'form-control form-control-lg', 'placeholder' => __('Project')]) }}
    @elseif($clients->count() == 0)
        <div class="alert alert-warning">
            <strong>{{ __("There's no client in the system.") }}</strong>
            @can('create clients')
                <a href="{{ route('stift.client.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create client') }}
                </a>
            @else
                <i class="zmdi zmdi-mood-bad"></i> {{ __("Unfortunately you can't create one") }}
            @endcan
        </div>
    @else
        <?php $client = $clients->first(); ?>
        {{ Form::hidden('client_id', $client->id) }}
        <label class="text-muted">{{ __('Client') }}: {{ $client->name() }}</label>
    @endif

    @if ($errors->has('project_id'))
        <div class="form-control-feedback">{{ $errors->first('project_id') }}</div>
    @endif
</div>

<div class="form-group row{{ $errors->has('is_active') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Active') }}</label>
    <div class="col-md-10">
        {{ Form::hidden('is_active', 0) }}
        <label class="switch switch-icon switch-pill switch-primary">
            {{ Form::checkbox('is_active', 1, null, ['class' => 'switch-input']) }}
            <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
            <span class="switch-handle"></span>
        </label>

        @if ($errors->has('is_active'))
            <div class="form-control-feedback">{{ $errors->first('is_active') }}</div>
        @endif

    </div>
</div>

<div class="form-group{{ $errors->has('users') ? ' has-danger' : '' }}">

    <legend>{{ __('Visible for users') }}</legend>

    @foreach($users as $user)
        {{ Form::checkbox("users[{$user->id}]", 1, $project->visibleFor($user), ['id' => 'users_' . $user->id]) }}
        <label for="users_{{$user->id}}">{{ $user->name }}</label>
    @endforeach

    @if ($errors->has('users'))
        <div class="form-control-feedback">{{ $errors->first('users') }}</div>
    @endif
</div>