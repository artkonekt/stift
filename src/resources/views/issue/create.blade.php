@extends('appshell::layouts.default')

@section('title')
    {{ __('Create issue') }}
@stop

@section('content')

    <div class="row">

        <div class="col-xl-9">
            <div class="card card-accent-success">
                <div class="card-header">
                    {{ __('Issue Details') }}
                </div>
                <div class="card-block">

                    {!! Form::model($issue, ['route' => 'stift.issue.store']) !!}

                    @include('stift::issue._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-success">{{ __('Create issue') }}</button>
                        <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>

    </div>

@stop
