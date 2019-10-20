@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing :label in :project', ['project' => $project->name, 'label' => $label->title]) }}
@stop

@section('content')
    {!! Form::model($label, ['url'  => route('stift.label.update', [$project, $label]), 'method' => 'PUT', 'class' => 'row']) !!}

    <div class="col-12 col-lg-8 col-xl-9">
        <div class="card card-accent-secondary">

            <div class="card-header">
                {{ $label->title }}
            </div>

            <div class="card-block">
                @include('stift::label._form')
            </div>

            <div class="card-footer">
                <button class="btn btn-primary">{{ __('Save') }}</button>
                <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
            </div>

        </div>
    </div>

    {!! Form::close() !!}
@stop
