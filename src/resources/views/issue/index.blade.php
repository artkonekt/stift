@extends('appshell::layouts.default')

@section('title')
    {{ __('Issues') }}
@stop

@section('content')

    @if ($errors->any())
        <div class="alert alert-warning">
            @foreach ($errors->all() as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif


    <div class="card card-accent-secondary">

        <div class="card-header">
            {{ __('Issues') }}

            @if($filteredProjects)
                @foreach($filteredProjects as $project)
                    <span class="badge badge-dark font-weight-normal">{{ $project->name }}</span>
                @endforeach
            @endif

            <div class="card-actionbar">
                <form action="{{ route('stift.issue.index') }}" class="form-inline">

                    {!! Form::select('projects[]', $projects, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('All projects')]) !!}
                    &nbsp;
                    {!! Form::select('status', $statuses, null, ['class' => 'form-control form-control-sm', 'placeholder' => __('Any status')]) !!}
                    &nbsp;
                    <button class="btn btn-sm btn-primary" type="submit">{{ __('Filter') }}</button>

                @can('create issues')
                    &nbsp;
                    <a href="{{ route('stift.issue.create') }}"
                       class="btn btn-sm btn-outline-success float-right">
                        <i class="zmdi zmdi-plus"></i>
                        {{ __('New Issue') }}
                    </a>
                @endcan
                </form>
            </div>

        </div>

        <div class="card-block">
            @include('stift::issue._list')
        </div>
    </div>

@stop
