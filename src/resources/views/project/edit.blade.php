@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing project') }} {{ $project->name }}
@stop

@section('content')

    <div class="row">

        <div class="col-xl-9">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Details') }}
                </div>
                <div class="card-block">

                    {!! Form::model($project, ['route' => ['stift.project.update', $project], 'method' => 'PUT']) !!}

                    @include('stift::project._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    @can('delete projects')
                        {!! Form::open(['route' => ['stift.project.destroy', $project], 'method' => 'DELETE']) !!}
                        <button class="btn btn-outline-danger float-right">
                            {{ __('Delete project') }}
                        </button>
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

    </div>


@stop