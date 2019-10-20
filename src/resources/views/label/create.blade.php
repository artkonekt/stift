@extends('appshell::layouts.default')

@section('title')
    {{ __('Add label to :project', ['project' => $project->name]) }}
@stop

@section('content')
    {!! Form::model($label, ['url' => route('stift.label.store', $project), 'autocomplete' => 'off', 'class' => 'row']) !!}
    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-success">
            <div class="card-header">
                {{ __('Label Details') }}
            </div>

            <div class="card-block">
                @include('stift::label._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-success">{{ __('Create label') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
@stop
