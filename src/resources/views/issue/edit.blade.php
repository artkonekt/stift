@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing issue') }} {{ $issue->subject }}
@stop

@section('content')

    <div class="row">

        <div class="col-xl-9">
            <div class="card card-accent-secondary">
                <div class="card-header">
                    {{ __('Details') }}
                </div>
                <div class="card-block">

                    {!! Form::model($issue, ['route' => ['stift.issue.update', $issue], 'method' => 'PUT']) !!}

                    @include('stift::issue._form')

                    <hr>
                    <div class="form-group">
                        <button class="btn btn-primary">{{ __('Save') }}</button>
                        <a href="#" onclick="history.back();" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
                    </div>

                    {!! Form::close() !!}
                </div>
                <div class="card-footer">
                    @can('delete issues')
                        {!! Form::open(['route' => ['stift.issue.destroy', $issue], 'method' => 'DELETE']) !!}
                        <button class="btn btn-outline-danger float-right">
                            {{ __('Delete issue') }}
                        </button>
                        {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

    </div>


@stop
