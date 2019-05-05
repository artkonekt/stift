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

<div class="form-group{{ $errors->has('slug') ? ' has-danger' : '' }}">
    {{ Form::text('slug', null, ['class' => 'form-control form-control-sm', 'placeholder' => __('Project key (leave empty to auto-generate)')]) }}
    @if ($errors->has('slug'))
        <div class="form-control-feedback">{{ $errors->first('slug') }}</div>
    @endif
</div>

<div class="form-group{{ $errors->has('project_id') ? ' has-danger' : '' }}">
    @if($customers->count() > 1)
        {{ Form::select('customer_id', $customers->pluck('name','id'), null, ['class' => 'form-control', 'placeholder' => __('Customer')]) }}
    @elseif($customers->count() == 0)
        <div class="alert alert-warning">
            <strong>{{ __("There's no customer in the system.") }}</strong>
            @can('create customers')
                <a href="{{ route('appshell.customer.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create customer') }}
                </a>
            @else
                <i class="zmdi zmdi-mood-bad"></i> {{ __("Unfortunately you can't create one") }}
            @endcan
        </div>
    @else
        <?php $customer = $customers->first(); ?>
        {{ Form::hidden('customer_id', $customer->id) }}
        <label class="text-muted">{{ __('Customer') }}: {{ $customer->getName() }}</label>
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

<div class="form-group row{{ $errors->has('is_billable') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Billable') }}</label>
    <div class="col-md-10">
        {{ Form::hidden('is_billable', 0) }}
        <label class="switch switch-icon switch-pill switch-primary">
            {{ Form::checkbox('is_billable', 1, null, ['class' => 'switch-input']) }}
            <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
            <span class="switch-handle"></span>
        </label>

        @if ($errors->has('is_billable'))
            <div class="form-control-feedback">{{ $errors->first('is_billable') }}</div>
        @endif

    </div>
</div>

<div class="form-group{{ $errors->has('users') ? ' has-danger' : '' }}">

    <legend>{{ __('Visible for users') }}</legend>

    @foreach($users as $user)
        {{ Form::checkbox("users[]", $user->id, $project->visibleFor($user), ['id' => 'users_' . $user->id]) }}
        <label for="users_{{$user->id}}">{{ $user->name }}</label>
    @endforeach

    @if ($errors->has('users'))
        <div class="form-control-feedback">{{ $errors->first('users') }}</div>
    @endif
</div>
